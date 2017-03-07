<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class FixedComposer
{
    /** @var array 固定データ */
    protected $Fixed;
    
    /**
     * コンストラクタ...
     */
    public function __construct()
    {
        $this->Fixed = \Config::get('Fixed');
    }

    /**
     * Bind data to the view.
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('Fixed', $this->Fixed);
    }
}