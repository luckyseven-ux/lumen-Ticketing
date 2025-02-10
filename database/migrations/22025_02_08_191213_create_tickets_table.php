    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Jalankan migration.
         */
        public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul tiket
            $table->text('description')->nullable(); // Deskripsi tiket
            $table->string('event_type')->nullable(); // Jenis acara
            $table->decimal('price', 10, 2)->after('event_type');
            $table->enum('status', ['available', 'sold_out', 'canceled'])->default('available'); // Status tiket
            $table->integer('available_seats')->default(100); // Jumlah kursi tersedia
            $table->dateTime('schedule'); // Jadwal acara
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke user
            $table->timestamps(); // created_at & updated_at otomatis
        });
    }   

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('tickets');
        }
    };
