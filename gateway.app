{   
    application, gateway,
    [   
        {description, "This is game gateway."},   
        {vsn, "1.0a"},   
        {modules, [yg] },   
        {registered, [game_gateway_sup]},   
        {applications, [kernel, stdlib, sasl]},   
        {mod, {game_gateway_app, []}},   
        {start_phases, []}   
    ]   
}.    
 
%% File end.  
