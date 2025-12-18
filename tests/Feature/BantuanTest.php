<?php

use App\Models\Bantuan;
use App\Models\Penerima;
use App\Models\User;

test('bantuan is automatically linked to all penerima when created', function () {
    // Create and authenticate a user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create some test penerima records
    $penerimas = Penerima::factory()->count(3)->create();
    
    // Bantuan data
    $bantuanData = [
        'nama_bantuan' => 'Test Bantuan',
        'deskripsi' => 'This is a test bantuan description',
        'tanggal' => now()->format('Y-m-d'),
    ];
    
    // Make a POST request to create a new bantuan
    $response = $this->post(route('bantuan.store'), $bantuanData);
    
    // Assert the request was successful
    $response->assertRedirect(route('bantuan.index'));
    
    // Get the created bantuan
    $bantuan = Bantuan::where('nama_bantuan', 'Test Bantuan')->first();
    
    // Assert the bantuan was created
    $this->assertNotNull($bantuan);
    
    // Assert all penerimas are linked to the bantuan
    $linkedPenerimas = $bantuan->penerimas;
    $this->assertEquals($penerimas->count(), $linkedPenerimas->count());
    
    // Assert each penerima is linked with the correct tanggal_diberikan
    foreach ($penerimas as $penerima) {
        $this->assertTrue($bantuan->penerimas->contains($penerima));
        $pivot = $bantuan->penerimas()->where('penerima_id', $penerima->id)->first()->pivot;
        $this->assertEquals($bantuan->tanggal, $pivot->tanggal_diberikan);
    }
});

test('bantuan creation with no existing penerimas still works', function () {
    // Create and authenticate a user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Ensure there are no penerimas
    Penerima::query()->delete();
    
    // Bantuan data
    $bantuanData = [
        'nama_bantuan' => 'Test Bantuan No Penerimas',
        'deskripsi' => 'This is a test bantuan description with no penerimas',
        'tanggal' => now()->format('Y-m-d'),
    ];
    
    // Make a POST request to create a new bantuan
    $response = $this->post(route('bantuan.store'), $bantuanData);
    
    // Assert the request was successful
    $response->assertRedirect(route('bantuan.index'));
    
    // Get the created bantuan
    $bantuan = Bantuan::where('nama_bantuan', 'Test Bantuan No Penerimas')->first();
    
    // Assert the bantuan was created
    $this->assertNotNull($bantuan);
    
    // Assert no penerimas are linked (since none exist)
    $this->assertEquals(0, $bantuan->penerimas->count());
});