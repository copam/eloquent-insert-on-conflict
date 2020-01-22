<?php

namespace InsertOnConflict\Tests\Unit;

use InsertOnConflict\Tests\Support\Models\User;
use InsertOnConflict\Tests\TestCase;

class InsertOnConflictTest extends TestCase
{

    protected $updatedUser = [
        'id'    => 1,
        'name'  => 'new name 1',
        'email' => 'new1@gmail.com',
    ];

    protected $updatedUser2 = [
        'id'    => 2,
        'name'  => 'new name 2',
        'email' => 'new2@gmail.com',
    ];

    /**
     * Seed the database.
     */
    public function setUp(): void
    {
        parent::setUp();

        User::insert([
            ['id' => 1, 'name' => 'old', 'email' => 'old@gmail.com'],
            ['id' => 2, 'name' => 'old', 'email' => 'old@gmail.com'],
            ['id' => 3, 'name' => 'old', 'email' => 'old@gmail.com'],
        ]);
    }

    /** @test */
    public function it_can_insert_on_conflict()
    {
        User::insertOnConflict([$this->updatedUser, $this->updatedUser2], null, 'do update set', 'id');

        $this->assertDatabaseHas('users', $this->updatedUser);
        $this->assertDatabaseHas('users', $this->updatedUser2);
    }
}
