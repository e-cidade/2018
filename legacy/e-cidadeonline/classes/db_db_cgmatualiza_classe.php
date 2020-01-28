<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_cgmatualiza
class cl_db_cgmatualiza { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $w11_sequencial = 0; 
   var $w11_nome = null; 
   var $w11_ender = null; 
   var $w11_numero = 0; 
   var $w11_compl = null; 
   var $w11_bairro = null; 
   var $w11_munic = null; 
   var $w11_uf = null; 
   var $w11_cep = null; 
   var $w11_cxpostal = null; 
   var $w11_cadast_dia = null; 
   var $w11_cadast_mes = null; 
   var $w11_cadast_ano = null; 
   var $w11_cadast = null; 
   var $w11_telef = null; 
   var $w11_ident = null; 
   var $w11_login = 0; 
   var $w11_incest = null; 
   var $w11_telcel = null; 
   var $w11_email = null; 
   var $w11_endcon = null; 
   var $w11_numcon = 0; 
   var $w11_comcon = null; 
   var $w11_baicon = null; 
   var $w11_muncon = null; 
   var $w11_ufcon = null; 
   var $w11_cepcon = null; 
   var $w11_cxposcon = null; 
   var $w11_telcon = null; 
   var $w11_celcon = null; 
   var $w11_emailc = null; 
   var $w11_nacion = 0; 
   var $w11_estciv = 0; 
   var $w11_profis = null; 
   var $w11_tipcre = 0; 
   var $w11_cgccpf = null; 
   var $w11_fax = null; 
   var $w11_nasc_dia = null; 
   var $w11_nasc_mes = null; 
   var $w11_nasc_ano = null; 
   var $w11_nasc = null; 
   var $w11_mae = null; 
   var $w11_sexo = null; 
   var $w11_ultalt_dia = null; 
   var $w11_ultalt_mes = null; 
   var $w11_ultalt_ano = null; 
   var $w11_ultalt = null; 
   var $w11_contato = null; 
   var $w11_hora = null; 
   var $w11_nomefanta = null; 
   var $w11_cnh = null; 
   var $w11_categoria = null; 
   var $w11_dtemissao_dia = null; 
   var $w11_dtemissao_mes = null; 
   var $w11_dtemissao_ano = null; 
   var $w11_dtemissao = null; 
   var $w11_dthabilitacao_dia = null; 
   var $w11_dthabilitacao_mes = null; 
   var $w11_dthabilitacao_ano = null; 
   var $w11_dthabilitacao = null; 
   var $w11_nomecomple = null; 
   var $w11_dtvencimento_dia = null; 
   var $w11_dtvencimento_mes = null; 
   var $w11_dtvencimento_ano = null; 
   var $w11_dtvencimento = null; 
   var $w11_revisado = 'f'; 
   var $w11_cgmnovo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w11_sequencial = int4 = Sequencial 
                 w11_nome = varchar(40) = Nome/Razão Social 
                 w11_ender = varchar(100) = Endereço 
                 w11_numero = int4 = Numero 
                 w11_compl = varchar(20) = Complemento 
                 w11_bairro = varchar(20) = Bairro 
                 w11_munic = varchar(20) = Município 
                 w11_uf = varchar(2) = UF 
                 w11_cep = varchar(8) = CEP 
                 w11_cxpostal = varchar(20) = Caixa Postal 
                 w11_cadast = date = Data do cadastramento 
                 w11_telef = varchar(12) = Telefone 
                 w11_ident = varchar(20) = Identidade 
                 w11_login = int4 = Login 
                 w11_incest = varchar(15) = Inscricao Estadual 
                 w11_telcel = varchar(12) = Celular 
                 w11_email = varchar(100) = email 
                 w11_endcon = varchar(100) = Endereco Comercial 
                 w11_numcon = int4 = Numero 
                 w11_comcon = varchar(20) = Complemento 
                 w11_baicon = varchar(20) = Bairro 
                 w11_muncon = varchar(20) = Municipio Comercial 
                 w11_ufcon = varchar(2) = Estado Comercial 
                 w11_cepcon = varchar(8) = CEP 
                 w11_cxposcon = varchar(20) = Caixa Postal 
                 w11_telcon = varchar(12) = Telefone comercial 
                 w11_celcon = varchar(12) = Celular comercial 
                 w11_emailc = varchar(100) = email comercial 
                 w11_nacion = int4 = Nacionalidade 
                 w11_estciv = int4 = Estado civil 
                 w11_profis = varchar(40) = Profissao 
                 w11_tipcre = int4 = Tipo de credor 
                 w11_cgccpf = varchar(14) = CNPJ/CPF 
                 w11_fax = varchar(12) = Fax 
                 w11_nasc = date = Nascimento 
                 w11_mae = varchar(40) = Mãe 
                 w11_sexo = varchar(1) = Sexo 
                 w11_ultalt = date = Ultima Alteração 
                 w11_contato = varchar(40) = Contato 
                 w11_hora = varchar(5) = Hora do Cadastramento 
                 w11_nomefanta = varchar(40) = Nome Fantasia 
                 w11_cnh = varchar(20) = CNH 
                 w11_categoria = varchar(2) = Categoria CNH 
                 w11_dtemissao = date = Emissão CNH 
                 w11_dthabilitacao = date = Primeira CNH 
                 w11_nomecomple = varchar(100) = Nome Completo 
                 w11_dtvencimento = date = Vencimento CNH 
                 w11_revisado = bool = Revisado 
                 w11_cgmnovo = bool = CGM novo 
                 ";
   //funcao construtor da classe 
   function cl_db_cgmatualiza() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cgmatualiza"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->w11_sequencial = ($this->w11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_sequencial"]:$this->w11_sequencial);
       $this->w11_nome = ($this->w11_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_nome"]:$this->w11_nome);
       $this->w11_ender = ($this->w11_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_ender"]:$this->w11_ender);
       $this->w11_numero = ($this->w11_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_numero"]:$this->w11_numero);
       $this->w11_compl = ($this->w11_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_compl"]:$this->w11_compl);
       $this->w11_bairro = ($this->w11_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_bairro"]:$this->w11_bairro);
       $this->w11_munic = ($this->w11_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_munic"]:$this->w11_munic);
       $this->w11_uf = ($this->w11_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_uf"]:$this->w11_uf);
       $this->w11_cep = ($this->w11_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cep"]:$this->w11_cep);
       $this->w11_cxpostal = ($this->w11_cxpostal == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cxpostal"]:$this->w11_cxpostal);
       if($this->w11_cadast == ""){
         $this->w11_cadast_dia = ($this->w11_cadast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cadast_dia"]:$this->w11_cadast_dia);
         $this->w11_cadast_mes = ($this->w11_cadast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cadast_mes"]:$this->w11_cadast_mes);
         $this->w11_cadast_ano = ($this->w11_cadast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cadast_ano"]:$this->w11_cadast_ano);
         if($this->w11_cadast_dia != ""){
            $this->w11_cadast = $this->w11_cadast_ano."-".$this->w11_cadast_mes."-".$this->w11_cadast_dia;
         }
       }
       $this->w11_telef = ($this->w11_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_telef"]:$this->w11_telef);
       $this->w11_ident = ($this->w11_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_ident"]:$this->w11_ident);
       $this->w11_login = ($this->w11_login == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_login"]:$this->w11_login);
       $this->w11_incest = ($this->w11_incest == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_incest"]:$this->w11_incest);
       $this->w11_telcel = ($this->w11_telcel == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_telcel"]:$this->w11_telcel);
       $this->w11_email = ($this->w11_email == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_email"]:$this->w11_email);
       $this->w11_endcon = ($this->w11_endcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_endcon"]:$this->w11_endcon);
       $this->w11_numcon = ($this->w11_numcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_numcon"]:$this->w11_numcon);
       $this->w11_comcon = ($this->w11_comcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_comcon"]:$this->w11_comcon);
       $this->w11_baicon = ($this->w11_baicon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_baicon"]:$this->w11_baicon);
       $this->w11_muncon = ($this->w11_muncon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_muncon"]:$this->w11_muncon);
       $this->w11_ufcon = ($this->w11_ufcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_ufcon"]:$this->w11_ufcon);
       $this->w11_cepcon = ($this->w11_cepcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cepcon"]:$this->w11_cepcon);
       $this->w11_cxposcon = ($this->w11_cxposcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cxposcon"]:$this->w11_cxposcon);
       $this->w11_telcon = ($this->w11_telcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_telcon"]:$this->w11_telcon);
       $this->w11_celcon = ($this->w11_celcon == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_celcon"]:$this->w11_celcon);
       $this->w11_emailc = ($this->w11_emailc == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_emailc"]:$this->w11_emailc);
       $this->w11_nacion = ($this->w11_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_nacion"]:$this->w11_nacion);
       $this->w11_estciv = ($this->w11_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_estciv"]:$this->w11_estciv);
       $this->w11_profis = ($this->w11_profis == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_profis"]:$this->w11_profis);
       $this->w11_tipcre = ($this->w11_tipcre == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_tipcre"]:$this->w11_tipcre);
       $this->w11_cgccpf = ($this->w11_cgccpf == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cgccpf"]:$this->w11_cgccpf);
       $this->w11_fax = ($this->w11_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_fax"]:$this->w11_fax);
       if($this->w11_nasc == ""){
         $this->w11_nasc_dia = ($this->w11_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_nasc_dia"]:$this->w11_nasc_dia);
         $this->w11_nasc_mes = ($this->w11_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_nasc_mes"]:$this->w11_nasc_mes);
         $this->w11_nasc_ano = ($this->w11_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_nasc_ano"]:$this->w11_nasc_ano);
         if($this->w11_nasc_dia != ""){
            $this->w11_nasc = $this->w11_nasc_ano."-".$this->w11_nasc_mes."-".$this->w11_nasc_dia;
         }
       }
       $this->w11_mae = ($this->w11_mae == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_mae"]:$this->w11_mae);
       $this->w11_sexo = ($this->w11_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_sexo"]:$this->w11_sexo);
       if($this->w11_ultalt == ""){
         $this->w11_ultalt_dia = ($this->w11_ultalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_ultalt_dia"]:$this->w11_ultalt_dia);
         $this->w11_ultalt_mes = ($this->w11_ultalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_ultalt_mes"]:$this->w11_ultalt_mes);
         $this->w11_ultalt_ano = ($this->w11_ultalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_ultalt_ano"]:$this->w11_ultalt_ano);
         if($this->w11_ultalt_dia != ""){
            $this->w11_ultalt = $this->w11_ultalt_ano."-".$this->w11_ultalt_mes."-".$this->w11_ultalt_dia;
         }
       }
       $this->w11_contato = ($this->w11_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_contato"]:$this->w11_contato);
       $this->w11_hora = ($this->w11_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_hora"]:$this->w11_hora);
       $this->w11_nomefanta = ($this->w11_nomefanta == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_nomefanta"]:$this->w11_nomefanta);
       $this->w11_cnh = ($this->w11_cnh == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_cnh"]:$this->w11_cnh);
       $this->w11_categoria = ($this->w11_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_categoria"]:$this->w11_categoria);
       if($this->w11_dtemissao == ""){
         $this->w11_dtemissao_dia = ($this->w11_dtemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dtemissao_dia"]:$this->w11_dtemissao_dia);
         $this->w11_dtemissao_mes = ($this->w11_dtemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dtemissao_mes"]:$this->w11_dtemissao_mes);
         $this->w11_dtemissao_ano = ($this->w11_dtemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dtemissao_ano"]:$this->w11_dtemissao_ano);
         if($this->w11_dtemissao_dia != ""){
            $this->w11_dtemissao = $this->w11_dtemissao_ano."-".$this->w11_dtemissao_mes."-".$this->w11_dtemissao_dia;
         }
       }
       if($this->w11_dthabilitacao == ""){
         $this->w11_dthabilitacao_dia = ($this->w11_dthabilitacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dthabilitacao_dia"]:$this->w11_dthabilitacao_dia);
         $this->w11_dthabilitacao_mes = ($this->w11_dthabilitacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dthabilitacao_mes"]:$this->w11_dthabilitacao_mes);
         $this->w11_dthabilitacao_ano = ($this->w11_dthabilitacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dthabilitacao_ano"]:$this->w11_dthabilitacao_ano);
         if($this->w11_dthabilitacao_dia != ""){
            $this->w11_dthabilitacao = $this->w11_dthabilitacao_ano."-".$this->w11_dthabilitacao_mes."-".$this->w11_dthabilitacao_dia;
         }
       }
       $this->w11_nomecomple = ($this->w11_nomecomple == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_nomecomple"]:$this->w11_nomecomple);
       if($this->w11_dtvencimento == ""){
         $this->w11_dtvencimento_dia = ($this->w11_dtvencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dtvencimento_dia"]:$this->w11_dtvencimento_dia);
         $this->w11_dtvencimento_mes = ($this->w11_dtvencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dtvencimento_mes"]:$this->w11_dtvencimento_mes);
         $this->w11_dtvencimento_ano = ($this->w11_dtvencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_dtvencimento_ano"]:$this->w11_dtvencimento_ano);
         if($this->w11_dtvencimento_dia != ""){
            $this->w11_dtvencimento = $this->w11_dtvencimento_ano."-".$this->w11_dtvencimento_mes."-".$this->w11_dtvencimento_dia;
         }
       }
       $this->w11_revisado = ($this->w11_revisado == "f"?@$GLOBALS["HTTP_POST_VARS"]["w11_revisado"]:$this->w11_revisado);
       $this->w11_cgmnovo = ($this->w11_cgmnovo == "f"?@$GLOBALS["HTTP_POST_VARS"]["w11_cgmnovo"]:$this->w11_cgmnovo);
     }else{
       $this->w11_sequencial = ($this->w11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w11_sequencial"]:$this->w11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w11_sequencial){ 
      $this->atualizacampos();
     if($this->w11_nome == null ){ 
       $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
       $this->erro_campo = "w11_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w11_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "w11_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w11_numero == null ){ 
       $this->w11_numero = "0";
     }
     if($this->w11_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "w11_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w11_cadast == null ){ 
       $this->w11_cadast = "null";
     }
     if($this->w11_login == null ){ 
       $this->w11_login = "0";
     }
     if($this->w11_numcon == null ){ 
       $this->w11_numcon = "0";
     }
     if($this->w11_nacion == null ){ 
       $this->w11_nacion = "0";
     }
     if($this->w11_estciv == null ){ 
       $this->w11_estciv = "0";
     }
     if($this->w11_tipcre == null ){ 
       $this->w11_tipcre = "0";
     }
     if($this->w11_nasc == null ){ 
       $this->w11_nasc = "null";
     }
     if($this->w11_ultalt == null ){ 
       $this->w11_ultalt = "null";
     }
     if($this->w11_dtemissao == null ){ 
       $this->w11_dtemissao = "null";
     }
     if($this->w11_dthabilitacao == null ){ 
       $this->w11_dthabilitacao = "null";
     }
     if($this->w11_dtvencimento == null ){ 
       $this->w11_dtvencimento = "null";
     }
     if($this->w11_revisado == null ){ 
       $this->erro_sql = " Campo Revisado nao Informado.";
       $this->erro_campo = "w11_revisado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w11_cgmnovo == null ){ 
       $this->erro_sql = " Campo CGM novo nao Informado.";
       $this->erro_campo = "w11_cgmnovo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w11_sequencial == "" || $w11_sequencial == null ){
       $result = db_query("select nextval('db_cgmatualiza_w11_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cgmatualiza_w11_seq_seq do campo: w11_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w11_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_cgmatualiza_w11_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $w11_sequencial)){
         $this->erro_sql = " Campo w11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w11_sequencial = $w11_sequencial; 
       }
     }
     if(($this->w11_sequencial == null) || ($this->w11_sequencial == "") ){ 
       $this->erro_sql = " Campo w11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cgmatualiza(
                                       w11_sequencial 
                                      ,w11_nome 
                                      ,w11_ender 
                                      ,w11_numero 
                                      ,w11_compl 
                                      ,w11_bairro 
                                      ,w11_munic 
                                      ,w11_uf 
                                      ,w11_cep 
                                      ,w11_cxpostal 
                                      ,w11_cadast 
                                      ,w11_telef 
                                      ,w11_ident 
                                      ,w11_login 
                                      ,w11_incest 
                                      ,w11_telcel 
                                      ,w11_email 
                                      ,w11_endcon 
                                      ,w11_numcon 
                                      ,w11_comcon 
                                      ,w11_baicon 
                                      ,w11_muncon 
                                      ,w11_ufcon 
                                      ,w11_cepcon 
                                      ,w11_cxposcon 
                                      ,w11_telcon 
                                      ,w11_celcon 
                                      ,w11_emailc 
                                      ,w11_nacion 
                                      ,w11_estciv 
                                      ,w11_profis 
                                      ,w11_tipcre 
                                      ,w11_cgccpf 
                                      ,w11_fax 
                                      ,w11_nasc 
                                      ,w11_mae 
                                      ,w11_sexo 
                                      ,w11_ultalt 
                                      ,w11_contato 
                                      ,w11_hora 
                                      ,w11_nomefanta 
                                      ,w11_cnh 
                                      ,w11_categoria 
                                      ,w11_dtemissao 
                                      ,w11_dthabilitacao 
                                      ,w11_nomecomple 
                                      ,w11_dtvencimento 
                                      ,w11_revisado 
                                      ,w11_cgmnovo 
                       )
                values (
                                $this->w11_sequencial 
                               ,'$this->w11_nome' 
                               ,'$this->w11_ender' 
                               ,$this->w11_numero 
                               ,'$this->w11_compl' 
                               ,'$this->w11_bairro' 
                               ,'$this->w11_munic' 
                               ,'$this->w11_uf' 
                               ,'$this->w11_cep' 
                               ,'$this->w11_cxpostal' 
                               ,".($this->w11_cadast == "null" || $this->w11_cadast == ""?"null":"'".$this->w11_cadast."'")." 
                               ,'$this->w11_telef' 
                               ,'$this->w11_ident' 
                               ,$this->w11_login 
                               ,'$this->w11_incest' 
                               ,'$this->w11_telcel' 
                               ,'$this->w11_email' 
                               ,'$this->w11_endcon' 
                               ,$this->w11_numcon 
                               ,'$this->w11_comcon' 
                               ,'$this->w11_baicon' 
                               ,'$this->w11_muncon' 
                               ,'$this->w11_ufcon' 
                               ,'$this->w11_cepcon' 
                               ,'$this->w11_cxposcon' 
                               ,'$this->w11_telcon' 
                               ,'$this->w11_celcon' 
                               ,'$this->w11_emailc' 
                               ,$this->w11_nacion 
                               ,$this->w11_estciv 
                               ,'$this->w11_profis' 
                               ,$this->w11_tipcre 
                               ,'$this->w11_cgccpf' 
                               ,'$this->w11_fax' 
                               ,".($this->w11_nasc == "null" || $this->w11_nasc == ""?"null":"'".$this->w11_nasc."'")." 
                               ,'$this->w11_mae' 
                               ,'$this->w11_sexo' 
                               ,".($this->w11_ultalt == "null" || $this->w11_ultalt == ""?"null":"'".$this->w11_ultalt."'")." 
                               ,'$this->w11_contato' 
                               ,'$this->w11_hora' 
                               ,'$this->w11_nomefanta' 
                               ,'$this->w11_cnh' 
                               ,'$this->w11_categoria' 
                               ,".($this->w11_dtemissao == "null" || $this->w11_dtemissao == ""?"null":"'".$this->w11_dtemissao."'")." 
                               ,".($this->w11_dthabilitacao == "null" || $this->w11_dthabilitacao == ""?"null":"'".$this->w11_dthabilitacao."'")." 
                               ,'$this->w11_nomecomple' 
                               ,".($this->w11_dtvencimento == "null" || $this->w11_dtvencimento == ""?"null":"'".$this->w11_dtvencimento."'")." 
                               ,'$this->w11_revisado' 
                               ,'$this->w11_cgmnovo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atualizacao do cgm ($this->w11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atualizacao do cgm já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atualizacao do cgm ($this->w11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8196,'$this->w11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1379,8196,'','".AddSlashes(pg_result($resaco,0,'w11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8148,'','".AddSlashes(pg_result($resaco,0,'w11_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8149,'','".AddSlashes(pg_result($resaco,0,'w11_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8150,'','".AddSlashes(pg_result($resaco,0,'w11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8151,'','".AddSlashes(pg_result($resaco,0,'w11_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8152,'','".AddSlashes(pg_result($resaco,0,'w11_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8153,'','".AddSlashes(pg_result($resaco,0,'w11_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8154,'','".AddSlashes(pg_result($resaco,0,'w11_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8155,'','".AddSlashes(pg_result($resaco,0,'w11_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8156,'','".AddSlashes(pg_result($resaco,0,'w11_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8157,'','".AddSlashes(pg_result($resaco,0,'w11_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8158,'','".AddSlashes(pg_result($resaco,0,'w11_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8159,'','".AddSlashes(pg_result($resaco,0,'w11_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8160,'','".AddSlashes(pg_result($resaco,0,'w11_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8161,'','".AddSlashes(pg_result($resaco,0,'w11_incest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8162,'','".AddSlashes(pg_result($resaco,0,'w11_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8163,'','".AddSlashes(pg_result($resaco,0,'w11_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8164,'','".AddSlashes(pg_result($resaco,0,'w11_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8165,'','".AddSlashes(pg_result($resaco,0,'w11_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8166,'','".AddSlashes(pg_result($resaco,0,'w11_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8167,'','".AddSlashes(pg_result($resaco,0,'w11_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8168,'','".AddSlashes(pg_result($resaco,0,'w11_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8169,'','".AddSlashes(pg_result($resaco,0,'w11_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8170,'','".AddSlashes(pg_result($resaco,0,'w11_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8171,'','".AddSlashes(pg_result($resaco,0,'w11_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8172,'','".AddSlashes(pg_result($resaco,0,'w11_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8173,'','".AddSlashes(pg_result($resaco,0,'w11_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8174,'','".AddSlashes(pg_result($resaco,0,'w11_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8175,'','".AddSlashes(pg_result($resaco,0,'w11_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8176,'','".AddSlashes(pg_result($resaco,0,'w11_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8177,'','".AddSlashes(pg_result($resaco,0,'w11_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8178,'','".AddSlashes(pg_result($resaco,0,'w11_tipcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8179,'','".AddSlashes(pg_result($resaco,0,'w11_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8180,'','".AddSlashes(pg_result($resaco,0,'w11_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8181,'','".AddSlashes(pg_result($resaco,0,'w11_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8182,'','".AddSlashes(pg_result($resaco,0,'w11_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8183,'','".AddSlashes(pg_result($resaco,0,'w11_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8184,'','".AddSlashes(pg_result($resaco,0,'w11_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8185,'','".AddSlashes(pg_result($resaco,0,'w11_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8186,'','".AddSlashes(pg_result($resaco,0,'w11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8187,'','".AddSlashes(pg_result($resaco,0,'w11_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8188,'','".AddSlashes(pg_result($resaco,0,'w11_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8189,'','".AddSlashes(pg_result($resaco,0,'w11_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8190,'','".AddSlashes(pg_result($resaco,0,'w11_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8191,'','".AddSlashes(pg_result($resaco,0,'w11_dthabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8192,'','".AddSlashes(pg_result($resaco,0,'w11_nomecomple'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8193,'','".AddSlashes(pg_result($resaco,0,'w11_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8194,'','".AddSlashes(pg_result($resaco,0,'w11_revisado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1379,8195,'','".AddSlashes(pg_result($resaco,0,'w11_cgmnovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w11_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_cgmatualiza set ";
     $virgula = "";
     if(trim($this->w11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_sequencial"])){ 
       $sql  .= $virgula." w11_sequencial = $this->w11_sequencial ";
       $virgula = ",";
       if(trim($this->w11_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "w11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w11_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_nome"])){ 
       $sql  .= $virgula." w11_nome = '$this->w11_nome' ";
       $virgula = ",";
       if(trim($this->w11_nome) == null ){ 
         $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
         $this->erro_campo = "w11_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w11_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_ender"])){ 
       $sql  .= $virgula." w11_ender = '$this->w11_ender' ";
       $virgula = ",";
       if(trim($this->w11_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "w11_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w11_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_numero"])){ 
        if(trim($this->w11_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w11_numero"])){ 
           $this->w11_numero = "0" ; 
        } 
       $sql  .= $virgula." w11_numero = $this->w11_numero ";
       $virgula = ",";
     }
     if(trim($this->w11_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_compl"])){ 
       $sql  .= $virgula." w11_compl = '$this->w11_compl' ";
       $virgula = ",";
     }
     if(trim($this->w11_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_bairro"])){ 
       $sql  .= $virgula." w11_bairro = '$this->w11_bairro' ";
       $virgula = ",";
     }
     if(trim($this->w11_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_munic"])){ 
       $sql  .= $virgula." w11_munic = '$this->w11_munic' ";
       $virgula = ",";
     }
     if(trim($this->w11_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_uf"])){ 
       $sql  .= $virgula." w11_uf = '$this->w11_uf' ";
       $virgula = ",";
     }
     if(trim($this->w11_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cep"])){ 
       $sql  .= $virgula." w11_cep = '$this->w11_cep' ";
       $virgula = ",";
       if(trim($this->w11_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "w11_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w11_cxpostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cxpostal"])){ 
       $sql  .= $virgula." w11_cxpostal = '$this->w11_cxpostal' ";
       $virgula = ",";
     }
     if(trim($this->w11_cadast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cadast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w11_cadast_dia"] !="") ){ 
       $sql  .= $virgula." w11_cadast = '$this->w11_cadast' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cadast_dia"])){ 
         $sql  .= $virgula." w11_cadast = null ";
         $virgula = ",";
       }
     }
     if(trim($this->w11_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_telef"])){ 
       $sql  .= $virgula." w11_telef = '$this->w11_telef' ";
       $virgula = ",";
     }
     if(trim($this->w11_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_ident"])){ 
       $sql  .= $virgula." w11_ident = '$this->w11_ident' ";
       $virgula = ",";
     }
     if(trim($this->w11_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_login"])){ 
        if(trim($this->w11_login)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w11_login"])){ 
           $this->w11_login = "0" ; 
        } 
       $sql  .= $virgula." w11_login = $this->w11_login ";
       $virgula = ",";
     }
     if(trim($this->w11_incest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_incest"])){ 
       $sql  .= $virgula." w11_incest = '$this->w11_incest' ";
       $virgula = ",";
     }
     if(trim($this->w11_telcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_telcel"])){ 
       $sql  .= $virgula." w11_telcel = '$this->w11_telcel' ";
       $virgula = ",";
     }
     if(trim($this->w11_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_email"])){ 
       $sql  .= $virgula." w11_email = '$this->w11_email' ";
       $virgula = ",";
     }
     if(trim($this->w11_endcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_endcon"])){ 
       $sql  .= $virgula." w11_endcon = '$this->w11_endcon' ";
       $virgula = ",";
     }
     if(trim($this->w11_numcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_numcon"])){ 
        if(trim($this->w11_numcon)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w11_numcon"])){ 
           $this->w11_numcon = "0" ; 
        } 
       $sql  .= $virgula." w11_numcon = $this->w11_numcon ";
       $virgula = ",";
     }
     if(trim($this->w11_comcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_comcon"])){ 
       $sql  .= $virgula." w11_comcon = '$this->w11_comcon' ";
       $virgula = ",";
     }
     if(trim($this->w11_baicon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_baicon"])){ 
       $sql  .= $virgula." w11_baicon = '$this->w11_baicon' ";
       $virgula = ",";
     }
     if(trim($this->w11_muncon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_muncon"])){ 
       $sql  .= $virgula." w11_muncon = '$this->w11_muncon' ";
       $virgula = ",";
     }
     if(trim($this->w11_ufcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_ufcon"])){ 
       $sql  .= $virgula." w11_ufcon = '$this->w11_ufcon' ";
       $virgula = ",";
     }
     if(trim($this->w11_cepcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cepcon"])){ 
       $sql  .= $virgula." w11_cepcon = '$this->w11_cepcon' ";
       $virgula = ",";
     }
     if(trim($this->w11_cxposcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cxposcon"])){ 
       $sql  .= $virgula." w11_cxposcon = '$this->w11_cxposcon' ";
       $virgula = ",";
     }
     if(trim($this->w11_telcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_telcon"])){ 
       $sql  .= $virgula." w11_telcon = '$this->w11_telcon' ";
       $virgula = ",";
     }
     if(trim($this->w11_celcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_celcon"])){ 
       $sql  .= $virgula." w11_celcon = '$this->w11_celcon' ";
       $virgula = ",";
     }
     if(trim($this->w11_emailc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_emailc"])){ 
       $sql  .= $virgula." w11_emailc = '$this->w11_emailc' ";
       $virgula = ",";
     }
     if(trim($this->w11_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_nacion"])){ 
        if(trim($this->w11_nacion)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w11_nacion"])){ 
           $this->w11_nacion = "0" ; 
        } 
       $sql  .= $virgula." w11_nacion = $this->w11_nacion ";
       $virgula = ",";
     }
     if(trim($this->w11_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_estciv"])){ 
        if(trim($this->w11_estciv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w11_estciv"])){ 
           $this->w11_estciv = "0" ; 
        } 
       $sql  .= $virgula." w11_estciv = $this->w11_estciv ";
       $virgula = ",";
     }
     if(trim($this->w11_profis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_profis"])){ 
       $sql  .= $virgula." w11_profis = '$this->w11_profis' ";
       $virgula = ",";
     }
     if(trim($this->w11_tipcre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_tipcre"])){ 
        if(trim($this->w11_tipcre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w11_tipcre"])){ 
           $this->w11_tipcre = "0" ; 
        } 
       $sql  .= $virgula." w11_tipcre = $this->w11_tipcre ";
       $virgula = ",";
     }
     if(trim($this->w11_cgccpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cgccpf"])){ 
       $sql  .= $virgula." w11_cgccpf = '$this->w11_cgccpf' ";
       $virgula = ",";
     }
     if(trim($this->w11_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_fax"])){ 
       $sql  .= $virgula." w11_fax = '$this->w11_fax' ";
       $virgula = ",";
     }
     if(trim($this->w11_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w11_nasc_dia"] !="") ){ 
       $sql  .= $virgula." w11_nasc = '$this->w11_nasc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w11_nasc_dia"])){ 
         $sql  .= $virgula." w11_nasc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->w11_mae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_mae"])){ 
       $sql  .= $virgula." w11_mae = '$this->w11_mae' ";
       $virgula = ",";
     }
     if(trim($this->w11_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_sexo"])){ 
       $sql  .= $virgula." w11_sexo = '$this->w11_sexo' ";
       $virgula = ",";
     }
     if(trim($this->w11_ultalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_ultalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w11_ultalt_dia"] !="") ){ 
       $sql  .= $virgula." w11_ultalt = '$this->w11_ultalt' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w11_ultalt_dia"])){ 
         $sql  .= $virgula." w11_ultalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->w11_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_contato"])){ 
       $sql  .= $virgula." w11_contato = '$this->w11_contato' ";
       $virgula = ",";
     }
     if(trim($this->w11_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_hora"])){ 
       $sql  .= $virgula." w11_hora = '$this->w11_hora' ";
       $virgula = ",";
     }
     if(trim($this->w11_nomefanta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_nomefanta"])){ 
       $sql  .= $virgula." w11_nomefanta = '$this->w11_nomefanta' ";
       $virgula = ",";
     }
     if(trim($this->w11_cnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cnh"])){ 
       $sql  .= $virgula." w11_cnh = '$this->w11_cnh' ";
       $virgula = ",";
     }
     if(trim($this->w11_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_categoria"])){ 
       $sql  .= $virgula." w11_categoria = '$this->w11_categoria' ";
       $virgula = ",";
     }
     if(trim($this->w11_dtemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_dtemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w11_dtemissao_dia"] !="") ){ 
       $sql  .= $virgula." w11_dtemissao = '$this->w11_dtemissao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w11_dtemissao_dia"])){ 
         $sql  .= $virgula." w11_dtemissao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->w11_dthabilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_dthabilitacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w11_dthabilitacao_dia"] !="") ){ 
       $sql  .= $virgula." w11_dthabilitacao = '$this->w11_dthabilitacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w11_dthabilitacao_dia"])){ 
         $sql  .= $virgula." w11_dthabilitacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->w11_nomecomple)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_nomecomple"])){ 
       $sql  .= $virgula." w11_nomecomple = '$this->w11_nomecomple' ";
       $virgula = ",";
     }
     if(trim($this->w11_dtvencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_dtvencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w11_dtvencimento_dia"] !="") ){ 
       $sql  .= $virgula." w11_dtvencimento = '$this->w11_dtvencimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w11_dtvencimento_dia"])){ 
         $sql  .= $virgula." w11_dtvencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->w11_revisado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_revisado"])){ 
       $sql  .= $virgula." w11_revisado = '$this->w11_revisado' ";
       $virgula = ",";
       if(trim($this->w11_revisado) == null ){ 
         $this->erro_sql = " Campo Revisado nao Informado.";
         $this->erro_campo = "w11_revisado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w11_cgmnovo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w11_cgmnovo"])){ 
       $sql  .= $virgula." w11_cgmnovo = '$this->w11_cgmnovo' ";
       $virgula = ",";
       if(trim($this->w11_cgmnovo) == null ){ 
         $this->erro_sql = " Campo CGM novo nao Informado.";
         $this->erro_campo = "w11_cgmnovo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w11_sequencial!=null){
       $sql .= " w11_sequencial = $this->w11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8196,'$this->w11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1379,8196,'".AddSlashes(pg_result($resaco,$conresaco,'w11_sequencial'))."','$this->w11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_nome"]))
           $resac = db_query("insert into db_acount values($acount,1379,8148,'".AddSlashes(pg_result($resaco,$conresaco,'w11_nome'))."','$this->w11_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_ender"]))
           $resac = db_query("insert into db_acount values($acount,1379,8149,'".AddSlashes(pg_result($resaco,$conresaco,'w11_ender'))."','$this->w11_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_numero"]))
           $resac = db_query("insert into db_acount values($acount,1379,8150,'".AddSlashes(pg_result($resaco,$conresaco,'w11_numero'))."','$this->w11_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_compl"]))
           $resac = db_query("insert into db_acount values($acount,1379,8151,'".AddSlashes(pg_result($resaco,$conresaco,'w11_compl'))."','$this->w11_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_bairro"]))
           $resac = db_query("insert into db_acount values($acount,1379,8152,'".AddSlashes(pg_result($resaco,$conresaco,'w11_bairro'))."','$this->w11_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_munic"]))
           $resac = db_query("insert into db_acount values($acount,1379,8153,'".AddSlashes(pg_result($resaco,$conresaco,'w11_munic'))."','$this->w11_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_uf"]))
           $resac = db_query("insert into db_acount values($acount,1379,8154,'".AddSlashes(pg_result($resaco,$conresaco,'w11_uf'))."','$this->w11_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cep"]))
           $resac = db_query("insert into db_acount values($acount,1379,8155,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cep'))."','$this->w11_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cxpostal"]))
           $resac = db_query("insert into db_acount values($acount,1379,8156,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cxpostal'))."','$this->w11_cxpostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cadast"]))
           $resac = db_query("insert into db_acount values($acount,1379,8157,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cadast'))."','$this->w11_cadast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_telef"]))
           $resac = db_query("insert into db_acount values($acount,1379,8158,'".AddSlashes(pg_result($resaco,$conresaco,'w11_telef'))."','$this->w11_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_ident"]))
           $resac = db_query("insert into db_acount values($acount,1379,8159,'".AddSlashes(pg_result($resaco,$conresaco,'w11_ident'))."','$this->w11_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_login"]))
           $resac = db_query("insert into db_acount values($acount,1379,8160,'".AddSlashes(pg_result($resaco,$conresaco,'w11_login'))."','$this->w11_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_incest"]))
           $resac = db_query("insert into db_acount values($acount,1379,8161,'".AddSlashes(pg_result($resaco,$conresaco,'w11_incest'))."','$this->w11_incest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_telcel"]))
           $resac = db_query("insert into db_acount values($acount,1379,8162,'".AddSlashes(pg_result($resaco,$conresaco,'w11_telcel'))."','$this->w11_telcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_email"]))
           $resac = db_query("insert into db_acount values($acount,1379,8163,'".AddSlashes(pg_result($resaco,$conresaco,'w11_email'))."','$this->w11_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_endcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8164,'".AddSlashes(pg_result($resaco,$conresaco,'w11_endcon'))."','$this->w11_endcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_numcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8165,'".AddSlashes(pg_result($resaco,$conresaco,'w11_numcon'))."','$this->w11_numcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_comcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8166,'".AddSlashes(pg_result($resaco,$conresaco,'w11_comcon'))."','$this->w11_comcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_baicon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8167,'".AddSlashes(pg_result($resaco,$conresaco,'w11_baicon'))."','$this->w11_baicon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_muncon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8168,'".AddSlashes(pg_result($resaco,$conresaco,'w11_muncon'))."','$this->w11_muncon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_ufcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8169,'".AddSlashes(pg_result($resaco,$conresaco,'w11_ufcon'))."','$this->w11_ufcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cepcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8170,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cepcon'))."','$this->w11_cepcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cxposcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8171,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cxposcon'))."','$this->w11_cxposcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_telcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8172,'".AddSlashes(pg_result($resaco,$conresaco,'w11_telcon'))."','$this->w11_telcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_celcon"]))
           $resac = db_query("insert into db_acount values($acount,1379,8173,'".AddSlashes(pg_result($resaco,$conresaco,'w11_celcon'))."','$this->w11_celcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_emailc"]))
           $resac = db_query("insert into db_acount values($acount,1379,8174,'".AddSlashes(pg_result($resaco,$conresaco,'w11_emailc'))."','$this->w11_emailc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_nacion"]))
           $resac = db_query("insert into db_acount values($acount,1379,8175,'".AddSlashes(pg_result($resaco,$conresaco,'w11_nacion'))."','$this->w11_nacion',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_estciv"]))
           $resac = db_query("insert into db_acount values($acount,1379,8176,'".AddSlashes(pg_result($resaco,$conresaco,'w11_estciv'))."','$this->w11_estciv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_profis"]))
           $resac = db_query("insert into db_acount values($acount,1379,8177,'".AddSlashes(pg_result($resaco,$conresaco,'w11_profis'))."','$this->w11_profis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_tipcre"]))
           $resac = db_query("insert into db_acount values($acount,1379,8178,'".AddSlashes(pg_result($resaco,$conresaco,'w11_tipcre'))."','$this->w11_tipcre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cgccpf"]))
           $resac = db_query("insert into db_acount values($acount,1379,8179,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cgccpf'))."','$this->w11_cgccpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_fax"]))
           $resac = db_query("insert into db_acount values($acount,1379,8180,'".AddSlashes(pg_result($resaco,$conresaco,'w11_fax'))."','$this->w11_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_nasc"]))
           $resac = db_query("insert into db_acount values($acount,1379,8181,'".AddSlashes(pg_result($resaco,$conresaco,'w11_nasc'))."','$this->w11_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_mae"]))
           $resac = db_query("insert into db_acount values($acount,1379,8182,'".AddSlashes(pg_result($resaco,$conresaco,'w11_mae'))."','$this->w11_mae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_sexo"]))
           $resac = db_query("insert into db_acount values($acount,1379,8183,'".AddSlashes(pg_result($resaco,$conresaco,'w11_sexo'))."','$this->w11_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_ultalt"]))
           $resac = db_query("insert into db_acount values($acount,1379,8184,'".AddSlashes(pg_result($resaco,$conresaco,'w11_ultalt'))."','$this->w11_ultalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_contato"]))
           $resac = db_query("insert into db_acount values($acount,1379,8185,'".AddSlashes(pg_result($resaco,$conresaco,'w11_contato'))."','$this->w11_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_hora"]))
           $resac = db_query("insert into db_acount values($acount,1379,8186,'".AddSlashes(pg_result($resaco,$conresaco,'w11_hora'))."','$this->w11_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_nomefanta"]))
           $resac = db_query("insert into db_acount values($acount,1379,8187,'".AddSlashes(pg_result($resaco,$conresaco,'w11_nomefanta'))."','$this->w11_nomefanta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cnh"]))
           $resac = db_query("insert into db_acount values($acount,1379,8188,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cnh'))."','$this->w11_cnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_categoria"]))
           $resac = db_query("insert into db_acount values($acount,1379,8189,'".AddSlashes(pg_result($resaco,$conresaco,'w11_categoria'))."','$this->w11_categoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_dtemissao"]))
           $resac = db_query("insert into db_acount values($acount,1379,8190,'".AddSlashes(pg_result($resaco,$conresaco,'w11_dtemissao'))."','$this->w11_dtemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_dthabilitacao"]))
           $resac = db_query("insert into db_acount values($acount,1379,8191,'".AddSlashes(pg_result($resaco,$conresaco,'w11_dthabilitacao'))."','$this->w11_dthabilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_nomecomple"]))
           $resac = db_query("insert into db_acount values($acount,1379,8192,'".AddSlashes(pg_result($resaco,$conresaco,'w11_nomecomple'))."','$this->w11_nomecomple',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_dtvencimento"]))
           $resac = db_query("insert into db_acount values($acount,1379,8193,'".AddSlashes(pg_result($resaco,$conresaco,'w11_dtvencimento'))."','$this->w11_dtvencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_revisado"]))
           $resac = db_query("insert into db_acount values($acount,1379,8194,'".AddSlashes(pg_result($resaco,$conresaco,'w11_revisado'))."','$this->w11_revisado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w11_cgmnovo"]))
           $resac = db_query("insert into db_acount values($acount,1379,8195,'".AddSlashes(pg_result($resaco,$conresaco,'w11_cgmnovo'))."','$this->w11_cgmnovo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atualizacao do cgm nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atualizacao do cgm nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w11_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w11_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8196,'$w11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1379,8196,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8148,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8149,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8150,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8151,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8152,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8153,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8154,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8155,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8156,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8157,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8158,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8159,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8160,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8161,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_incest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8162,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8163,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8164,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8165,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8166,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8167,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8168,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8169,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8170,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8171,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8172,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8173,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8174,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8175,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8176,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8177,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8178,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_tipcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8179,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8180,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8181,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8182,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8183,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8184,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8185,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8186,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8187,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8188,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8189,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8190,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8191,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_dthabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8192,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_nomecomple'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8193,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8194,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_revisado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1379,8195,'','".AddSlashes(pg_result($resaco,$iresaco,'w11_cgmnovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_cgmatualiza
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w11_sequencial = $w11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atualizacao do cgm nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atualizacao do cgm nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_cgmatualiza";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $w11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_cgmatualiza ";
     $sql2 = "";
     if($dbwhere==""){
       if($w11_sequencial!=null ){
         $sql2 .= " where db_cgmatualiza.w11_sequencial = $w11_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $w11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_cgmatualiza ";
     $sql2 = "";
     if($dbwhere==""){
       if($w11_sequencial!=null ){
         $sql2 .= " where db_cgmatualiza.w11_sequencial = $w11_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>