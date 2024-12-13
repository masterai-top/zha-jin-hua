{   
    application, local_gateway,
    [   
        {description, "This is local gateway."},   
        {vsn, "1.0a"},    
        {registered, [local_gateway_sup]},   
        {applications, [kernel, stdlib, sasl]},   
        {mod, {local_gateway_app, []}},   
        {start_phases, []}   
    ]   
}.    
 
%% File end.