# Roupichas-Bonequetes

## Objetivos


### Stack Tecnlógico
- Backend: PHP estruturado com sessões nativas
- Banco de dados: MySQL (PDO para segurança)
- Frontend: HTML5, PHP, CSS, Tailwind CSS

#### Regras de negócio (CORE)
Tratar senhas de usuários com hash bcript
O sistema deve ter uma página de históricos e manter sempre os logs de qualquer alteração feita por 
qualquer usuário, para auditorias futuras.
- o usuário clica em jogar, então abre uma seleção de bonequinhos "avatares".
- em outra tela vai ter um catalogo de roupas e acessórios para personalizar.
  as personalizações são: CABELO, ROSTOS, ROUPAS SAPATOS E ACESSÓRIOS.
  NOS CABELOS PODEMOS ESCOLHER TIPOS E CORES
  NOS ROSTOS VAI TER VARIOS TIPOS DE ROSOS PRONTOS, TIPO ROSTINHOS DA POLLY MAS PODEM SER OUTROS TIPOS
  NAS ROUPAS VAI SER MODULAR, BLUSAS, CALÇAS, SAIAS SHORTS E ETC.
  VARIOS TIPOS DE SAPATOS, E ACESSÓRIOS.
  QUALQUER PESSOA PODE ENVIAR AS SPRITES EM PNG PRA PERSONALIZR TODOS OS ITENS PERSONALIZAVEIS. NÃO SE LIMITANDO AOS ITENS JA ACRESCIDOS NO JOGO.


##### Regras Globais
- Use sempre PDO para conexão e queries no MySQL para evitar SQL Injections
- Mantenha o código limpo e comente apenas logicas complexas.
- Separe os arquivos de forma lógica: um arquivo para conexão com a base (bd.php) e scripts de backend isolados e views em HTML5/PHP, nunca faça o sistema como um monolito, deixe sempre separados todas as regras para facilitar os futuros upgrades.
 - Estilize as telas em Tailwind de forma responsiva priorizando o MobileFrist.
 - Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)
 - sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL
