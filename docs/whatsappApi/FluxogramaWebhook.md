[ Sistema recebe requisição no Webhook ]
        |
        v
[ Verifica se já recebeu esta mensagem anteriormente ]
        |
        |---> Sim
        |        v
        |   [ Encerra o processo ]
        |
        |---> Não
                v
[ Verifica se o Customer já existe na base para esta company
  (Customer::where('phone', $request->phone)
           ->where('company_id', $request->company_id)) ]
        |
        |---> Customer NÃO existe
        |        v
        |   [ Dispara Job para responder com link genérico de cadastro ]
        |        v
        |   [ Encerra o processo ]
        |
        |---> Customer JÁ existe
                 v
        [ Verifica se já existe um ChatSession para este Customer ]
                 |
                 |---> NÃO existe
                 |        v
                 |   [ Cria ChatSession ]
                 |
                 |---> JÁ existe
                 |        v
                 |   [ Salva Message vinculando ao ChatSession ]
                 |
                 v
        [ Dispara Job para responder com link personalizado do Customer ]
                 v
        [ Encerra o processo ]
