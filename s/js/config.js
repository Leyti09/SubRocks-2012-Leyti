var sr_config = {};
sr_config.account = {};
sr_config.watch = {};

sr_config.account.loggedIn = false;
sr_config.account.subscribed = false;
sr_config.watch.ownAccount = false;
sr_config.watch.commented = false;

sr_config = { 
    update_config: function(config, new_value) {
        try {
            if(Array.isArray(config)) {
                for (const val of config) {
                    sr_config.val[0].val[1] = val[1];
                    console.log(val);
                }
            } else {
                config = new_value;
                return 0;
            }
        } catch (error) {
            return 1;
        }
    }
}