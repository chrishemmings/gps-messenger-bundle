Google Pub/Sub transport implementation for Symfony Messenger
========

This bundle provides a simple implementation of Google Pub/Sub transport for Symfony Messenger.

[![GPS Messenger Bundle CI](https://github.com/chrishemmings/gps-messenger-bundle/actions/workflows/php.yml/badge.svg)](https://github.com/chrishemmings/gps-messenger-bundle/actions/workflows/php.yml)

The bundle requires only `symfony/messenger`, `google/cloud-pubsub` and `symfony/options-resolver` packages.
In contrast with [Enqueue GPS transport](https://github.com/php-enqueue/gps),
it doesn't require [Enqueue](https://github.com/php-enqueue)
and [some bridge](https://github.com/sroze/messenger-enqueue-transport#readme).
It supports ordering messages with `OrderingKeyStamp` and it's not outdated.

## Installation

### Step 1: Install the Bundle

From within container execute the following command to download the latest version of the bundle:

```console
$ composer require petitpress/gps-messenger-bundle --no-scripts
```

### Step 2: Configure Symfony Messenger
```yaml
# config/packages/messenger.yaml

framework:
    messenger:
        transports:
            gps_messages:
                dsn: 'gps://'
                options:
                    topic_name: 'topic_name'
                    subscription_name: 'subscription_name'
                    key_file_path: 'path/to/key.json'
                    project_id: 'project_id'
                    max_messages_pull: 10
```

### Step 3: Use available stamps if needed

* `OrderingKeyStamp`: use for keeping messages of the same context in order.
  For more information, read an [official documentation](https://cloud.google.com/pubsub/docs/publisher#using_ordering_keys).
