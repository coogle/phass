<?php

namespace Phass\Entity\Subscription\Notification;

class Share extends \Phass\Entity\Subscription\Notification\AbstractNotification implements TimelineItemGetterInterface
{
    use \Phass\Entity\Subscription\Notification\GetItemTrait;
}