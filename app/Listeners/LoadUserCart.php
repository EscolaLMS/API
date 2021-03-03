<?php

namespace App\Listeners;

use Treestoneit\ShoppingCart\CartContract;

class LoadUserCart
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
        $this->cart->loadUserCart($event->getUser());
    }
}
