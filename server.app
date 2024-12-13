{
    application, server,
    [
        {description, "This is game server."},
        {vsn, "1.0a"},
        {modules, [yg]},
        {registered, [game_server_sup]},
        {applications, [kernel, stdlib, sasl]},
        {strict_md5, 1},			%% 是否需要严格验证 （1：验证; 0: 不验证）
        {platform, "4399"},			%% 运营平台名称
        {mod, {game_server_app, []}},
        {env,[{platform,"4399"},{card_key,"Cdf*75EpervREawo%$"}]},
        {start_phases, []}
    ]
}.

%% File end.
