<?php

namespace views;


class ErrorView extends AbstractView
{

    private $message;

    protected function outputHTML()
    {
        ?>
        <div class="page container center_div">
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                <?php echo $this->message; ?>
            </div>
        </div>
        <?php
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

}