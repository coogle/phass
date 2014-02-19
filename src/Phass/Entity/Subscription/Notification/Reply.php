<?php

namespace Phass\Entity\Subscription\Notification;

class Reply extends \Phass\Entity\Subscription\Notification\AbstractNotification implements TimelineItemGetterInterface
{
    use \Phass\Entity\Subscription\Notification\GetItemTrait;
}