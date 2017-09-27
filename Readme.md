# WooCommerce Braspress #
**Tags:** shipping, delivery, woocommerce, braspress  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Integration between the Braspress and WooCommerce

## Integrado com a nova API de integração Braspress.
- Última validação 19 de Setembro 2017.

## Description ##

Utilize os métodos de entrega e serviços da Braspress com a sua loja WooCommerce.

[Braspress](http://www.braspress.com.br/) é um método de entrega brasileiro.

O plugin WooCommerce Braspress foi desenvolvido sem nenhum incentivo da Braspress. O desenvolvedor deste plugin não possuem vínculos com esta empresa. E note que este plugin foi feito baseado na documentação.

### Serviços integrados ###

Estão integrados os seguintes serviços:

- Braspress Aéreo
- Braspress Rodoviário
- Braspress Aéreo - FIB ( Pagamento na retirada )
- Braspress Rodoviário - FIB ( Pagamento na retirada )

### Instalação do plugin: ###

- Envie os arquivos do plugin para a pasta wp-content/plugins, ou instale usando o instalador de plugins do WordPress.
- Ative o plugin.

### Configurações dos produtos ###

É necessário configurar o **peso** e **dimensões** de todos os seus produtos, caso você queria que a cotação de frete seja exata.
Note que é possível configurar com produtos do tipo **simples** ou **variável** e não *virtuais* (produtos virtuais são ignorados na hora de cotar o frete).  

Alternativamente, você pode configurar apenas o peso e deixar as dimensões em branco, pois serão utilizadas as configurações do **Pacote Padrão** para as dimensões (neste caso pode ocorrer uma variação pequena no valor do frete, pois a Braspress considera mais o peso do que as dimensões para a cotação).

## Frequently Asked Questions ##

### Qual é a licença do plugin? ###

Este plugin esta licenciado como GPL.

### O que eu preciso para utilizar este plugin? ###

* WooCommerce 2.6 ou superior.
* Adicionar peso e dimensões nos produtos que pretende entregar.

### Quais são os métodos de entrega que o plugin aceita? ###

São aceitos os seguintes métodos de entrega nacionais:

- Braspress Aéreo
- Braspress Rodoviário
- Braspress Aéreo - FIB ( Pagamento na retirada )
- Braspress Rodoviário - FIB ( Pagamento na retirada )

### Onde configuro os métodos de entrega? ###

Os métodos de entrega devem ser configurados em "WooCommerce" > "Configurações" > "Entrega" > "Áreas de entrega".

Para entrega nacional, é necessário criar uma área de entrega para o Brasil ou para determinados estados brasileiros e atribuir os métodos de entrega.

### Como é feita a cotação do frete? ###

A cotação do frete é feita utilizando o [site da braspress](http://braspress.com.br).

Na cotação do frete é usado o seu CEP de origem, CEP de destino do cliente, junto com as dimensões dos produtos e peso. Desta forma o valor cotado sera o mais próximo possível do real.

### Tem calculadora de frete na página do produto? ###

Não tem, simplesmente porque não faz parte do escopo deste plugin.

Escopo deste plugin é prover integração entre o WooCommerce e a Braspress.

### Este plugin faz alterações na calculadora de frete na página do carrinho ou na de finalização? ###

Sim, para simulação do frete na Braspress é necessário informar o CPF/CNPJ, dessa forma o campo é incluido dinâmicamente se o método de frete estiver ativo.
De qualquer forma, este plugin funciona esperando o WooCommerce verificar pelos valores de entrega, então é feita uma conexão com a Braspress e os valores retornados são passados de volta para o WooCommerce apresentar.

Note que não damos suporte para qualquer tipo de personalização na calculadora, caso você queria mudar algo como aparece, deve procurar ajuda com o WooCommerce e não com este plugin.

### Os métodos de entrega da Braspress não aparecem no carrinho ou durante a finalização? ###

Isso ocorre quando alguma informação não for respassada ou for repassada incorretamente, ex: CEP, CPF/CNPJ, Peso ou Dimensões.

### O valor do frete calculado não bateu com a do site da Braspress? ###

Este plugin utiliza o Webservices da Braspress para calcular o frete e quando este tipo de problema acontece geralmente é porque:

1. Foram configuradas de forma errada as opções de peso e dimensões dos produtos na loja.
2. Configurado errado o CEP de origem nos métodos de entrega.
3. Clientes conveniados também possuem desconto no frete, dessa forma se o CPF informado for diferente, os valores serão diferente.
4. O Webservices dos Braspress enviou um valor diferente! Sim isso acontece, pois a cotação é dinâmica.

## Screenshots ##

### 1. Exemplo de áreas de entrega com a Braspress. ###
![Exemplo de áreas de entrega com Braspress.](https://user-images.githubusercontent.com/390882/30592451-28d39502-9d1d-11e7-9f70-f10f531528aa.png)

### 2. Exemplo da tela de configurações dos métodos de entrega. ###
![Exemplo da tela de configurações dos métodos de entrega.](https://user-images.githubusercontent.com/390882/30592417-0bd909fa-9d1d-11e7-9df1-58d49418a3a1.png)

### 3. Exemplo dos métodos de entrega sendo exibidos na página de finalização. ###
![Exemplo dos métodos de entrega sendo exibidos na página de finalização.](https://user-images.githubusercontent.com/390882/30592463-34ed6c8c-9d1d-11e7-82b4-180c0a9d137a.png)
