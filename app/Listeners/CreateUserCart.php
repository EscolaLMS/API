<?php

namespace App\Listeners;

use Treestoneit\ShoppingCart\CartContract;

class CreateUserCart
{
    private CartContract $cart;

    /**
     * CreateUserCart constructor.
     * @param CartContract $cart
     */
    public function __construct(CartContract $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $this->cart->attachTo($event->user);
    }
}
