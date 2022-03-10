<?php

namespace Database\Seeders;

use EscolaLms\Auth\Models\User;
use EscolaLms\Cart\Models\Order;
use EscolaLms\Cart\Models\OrderItem;
use EscolaLms\Consultations\Models\Consultation;
use EscolaLms\Consultations\Models\ConsultationProposedTerm;
use EscolaLms\Consultations\Models\ConsultationTerm;
use Illuminate\Database\Seeder;

class ConsultationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create();
        Consultation::factory(10)
            ->has(ConsultationProposedTerm::factory(3), 'proposedTerms')
            ->create()
            ->each(function (Consultation $consultation) use($user) {
                $consultationsForOrder = collect();
                $consultationsForOrder->push($consultation);
                $price = $consultationsForOrder->reduce(fn ($acc, Consultation $consultation) => $acc + $consultation->getBuyablePrice(), 0);
                $this->order = Order::factory()->afterCreating(
                    function (Order $order) use($consultationsForOrder, $user) {
                        $items = $order->items()->saveMany(
                            $consultationsForOrder->map(
                                function (Consultation $consultation) {
                                    return OrderItem::query()->make([
                                        'quantity' => 1,
                                        'buyable_id' => $consultation->getKey(),
                                        'buyable_type' => Consultation::class,
                                    ]);
                                }
                            )
                        );
                        $items->each(fn (OrderItem $orderItem) =>
                        ConsultationTerm::factory([
                            'order_item_id' => $orderItem->getKey(),
                            'user_id' => $user->getKey()
                        ])->create()
                        );
                    }
                )->create([
                    'user_id' => $user->getKey(),
                    'total' => $price,
                    'subtotal' => $price,
                ]);
            });
    }
}
