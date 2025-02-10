<?php
 namespace App\Http\Controllers;

 use App\Models\Order;
 use App\Models\Ticket;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 
 class OrderController extends Controller
 {
    public function index(){
        $user=auth()->user();
        return response()->json(Order::where('user_id',$user->id)->with('ticket')->get());
    }

    public function store(Request $request)
    {
        $user=auth()->user();
        $ticket=Ticket::find($request->ticket_id);

        if(!$ticket){
            return response()->json([
                'message'=>'Ticket Not Found !!'
            ],404);
        }
        if(!$ticket->avaiable_seats<=0){
            return response()->json([
                'message'=>'All tickets Has Been Sold out'
            ],400);
        }

        //create order
        $order=Order::create([
            'user_id'=>$user->id,
            'ticket_id'=>$ticket->id,
            'quantity'=>$request->quantity,
            'status'=>'pending'//status awal pednding
            
        ]);
        //kurangi jumlah ticket yang ada
        $ticket->update(['avaiable_seats'=>$ticket->avaiable_seats-1]);
        
        return response()->json(['message'=>'Your Ticket Booked Succesfully !!']);
    }

    public function updatePayment(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::find($id);

        if (!$order || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found or unauthorized'], 403);
        }

        $order->update(['status' => 'paid']);
        return response()->json(['message' => 'Payment updated successfully', 'order' => $order]);
    }

    public function cancel($id)
    {
        $user = auth()->user();
        $order = Order::find($id);

        if (!$order || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found or unauthorized'], 403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['message' => 'Cannot cancel a paid order'], 400);
        }

        // Kembalikan tiket yang tersedia
        $order->ticket->update(['available_seats' => $order->ticket->available_seats + 1]);

        // Hapus pesanan
        $order->delete();

        return response()->json(['message' => 'Order canceled successfully']);
    }

 }