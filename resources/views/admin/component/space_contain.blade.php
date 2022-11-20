<?php
    use Jenssegers\Agent\Agent;
    use App\Models\Pageview;
    $agent = new Agent();
    $pageview = Pageview::find(1);
    ?>
    @if($agent->isMobile())
    <div class="card my-3 bg-primary">
        <div class="my-5">

        </div>
    </div>
    @endif
