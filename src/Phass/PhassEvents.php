<?php

namespace Phass;

class PhassEvents
{
    /**
     * The following are all events triggered when we recieve a Google Glass
     * notification from a timeline subscription
     */
    const EVENT_SUBSCRIPTION_SHARE = 'Phass\Events\Subscription\Share';
    const EVENT_SUBSCRIPTION_DELETE = 'Phass\Events\Subscription\Delete';
    const EVENT_SUBSCRIPTION_LAUNCH = 'Phass\Events\Subscription\Launch';
    const EVENT_SUBSCRIPTION_REPLY = 'Phass\Events\Subscription\Reply';
    const EVENT_SUBSCRIPTION_LOCATION = 'Phass\Events\Subscription\Location';
    const EVENT_SUBSCRIPTION_CUSTOM = 'Phass\Events\Subscription\Custom';

    /**
     * When a timeline subscription notification is received this is triggered in an attempt
     * to resolve the opaque user ID given to us by Google (representing which user did the action)
     * to an OAuth2 token for that user. It allows us to do things like insert timeline items
     * when a notification is received.
     */
    const EVENT_SUBSCRIPTION_RESOLVE_USER = 'Phass\Events\Subscription\ResolveUser';

}