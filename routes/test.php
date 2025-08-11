<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::get('/test-users', function () {
    $users = User::all(['name', 'email', 'role']);
    
    echo "<h2>All Users in Database:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Email</th><th>Role</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user->name}</td>";
        echo "<td>{$user->email}</td>";
        echo "<td>{$user->role}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Password Test:</h3>";
    $adminUser = User::where('email', 'admin@smansan.sch.id')->first();
    if ($adminUser) {
        $passwordCheck = Hash::check('password', $adminUser->password);
        echo "Admin password check: " . ($passwordCheck ? 'VALID' : 'INVALID') . "<br>";
    } else {
        echo "Admin user not found!<br>";
    }
});

Route::get('/fix-passwords', function () {
    $users = [
        'admin@smansan.sch.id' => 'Administrator',
        'teacher@smansan.sch.id' => 'Teacher Demo',
        'student@smansan.sch.id' => 'Student Demo'
    ];
    
    foreach ($users as $email => $name) {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->password = Hash::make('password');
            $user->save();
            echo "Updated password for: {$email}<br>";
        }
    }
    
    echo "All passwords updated!";
});
