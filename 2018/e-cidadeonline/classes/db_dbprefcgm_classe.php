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
//CLASSE DA ENTIDADE dbprefcgm
class cl_dbprefcgm { 
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
   var $z01_sequencial = 0; 
   var $z01_baicon = null; 
   var $z01_bairro = null; 
   var $z01_nome = null; 
   var $z01_ender = null; 
   var $z01_numero = 0; 
   var $z01_compl = null; 
   var $z01_munic = null; 
   var $z01_uf = null; 
   var $z01_cep = null; 
   var $z01_cxpostal = null; 
   var $z01_cadast_dia = null; 
   var $z01_cadast_mes = null; 
   var $z01_cadast_ano = null; 
   var $z01_cadast = null; 
   var $z01_telef = null; 
   var $z01_ident = null; 
   var $z01_login = 0; 
   var $z01_incest = null; 
   var $z01_telcel = null; 
   var $z01_email = null; 
   var $z01_endcon = null; 
   var $z01_numcon = 0; 
   var $z01_comcon = null; 
   var $z01_muncon = null; 
   var $z01_ufcon = null; 
   var $z01_cepcon = null; 
   var $z01_cxposcon = null; 
   var $z01_telcon = null; 
   var $z01_celcon = null; 
   var $z01_emailc = null; 
   var $z01_nacion = 0; 
   var $z01_estciv = 0; 
   var $z01_profis = null; 
   var $z01_tipcre = 0; 
   var $z01_cgccpf = null; 
   var $z01_fax = null; 
   var $z01_nasc_dia = null; 
   var $z01_nasc_mes = null; 
   var $z01_nasc_ano = null; 
   var $z01_nasc = null; 
   var $z01_pai = null; 
   var $z01_mae = null; 
   var $z01_sexo = null; 
   var $z01_ultalt_dia = null; 
   var $z01_ultalt_mes = null; 
   var $z01_ultalt_ano = null; 
   var $z01_ultalt = null; 
   var $z01_contato = null; 
   var $z01_hora = null; 
   var $z01_nomefanta = null; 
   var $z01_cnh = null; 
   var $z01_categoria = null; 
   var $z01_dtemissao_dia = null; 
   var $z01_dtemissao_mes = null; 
   var $z01_dtemissao_ano = null; 
   var $z01_dtemissao = null; 
   var $z01_nomecomple = null; 
   var $z01_dtvencimento_dia = null; 
   var $z01_dtvencimento_mes = null; 
   var $z01_dtvencimento_ano = null; 
   var $z01_dtvencimento = null; 
   var $z01_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z01_sequencial = int4 = Código sequencial 
                 z01_baicon = varchar(40) = Bairro 
                 z01_bairro = varchar(40) = Bairro 
                 z01_nome = varchar(40) = Nome/Razão Social 
                 z01_ender = varchar(100) = Endereço 
                 z01_numero = int4 = Numero 
                 z01_compl = varchar(20) = Complemento 
                 z01_munic = varchar(40) = Município 
                 z01_uf = varchar(2) = UF 
                 z01_cep = varchar(8) = CEP 
                 z01_cxpostal = varchar(20) = Caixa Postal 
                 z01_cadast = date = Data do cadastramento 
                 z01_telef = varchar(12) = Telefone 
                 z01_ident = varchar(20) = Identidade 
                 z01_login = int4 = Login 
                 z01_incest = varchar(15) = Inscricao Estadual 
                 z01_telcel = varchar(12) = Celular 
                 z01_email = varchar(100) = email 
                 z01_endcon = varchar(100) = Endereco Comercial 
                 z01_numcon = int4 = Numero 
                 z01_comcon = varchar(20) = Complemento 
                 z01_muncon = varchar(40) = Municipio Comercial 
                 z01_ufcon = varchar(2) = Estado Comercial 
                 z01_cepcon = varchar(8) = CEP 
                 z01_cxposcon = varchar(20) = Caixa Postal 
                 z01_telcon = varchar(12) = Telefone comercial 
                 z01_celcon = varchar(12) = Celular comercial 
                 z01_emailc = varchar(100) = email comercial 
                 z01_nacion = int4 = Nacionalidade 
                 z01_estciv = int4 = Estado civil 
                 z01_profis = varchar(40) = Profissao 
                 z01_tipcre = int4 = Tipo de credor 
                 z01_cgccpf = varchar(14) = CNPJ/CPF 
                 z01_fax = varchar(12) = Fax 
                 z01_nasc = date = Nascimento 
                 z01_pai = varchar(40) = Pai 
                 z01_mae = varchar(40) = Mãe 
                 z01_sexo = varchar(1) = Sexo 
                 z01_ultalt = date = Ultima Alteração 
                 z01_contato = varchar(40) = Contato 
                 z01_hora = varchar(5) = Hora do Cadastramento 
                 z01_nomefanta = varchar(40) = Nome Fantasia 
                 z01_cnh = varchar(20) = CNH 
                 z01_categoria = varchar(2) = Categoria CNH 
                 z01_dtemissao = date = Emissão CNH 
                 z01_nomecomple = varchar(100) = Nome Completo 
                 z01_dtvencimento = date = Vencimento CNH 
                 z01_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_dbprefcgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dbprefcgm"); 
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
       $this->z01_sequencial = ($this->z01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_sequencial"]:$this->z01_sequencial);
       $this->z01_baicon = ($this->z01_baicon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_baicon"]:$this->z01_baicon);
       $this->z01_bairro = ($this->z01_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_bairro"]:$this->z01_bairro);
       $this->z01_nome = ($this->z01_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nome"]:$this->z01_nome);
       $this->z01_ender = ($this->z01_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_ender"]:$this->z01_ender);
       $this->z01_numero = ($this->z01_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_numero"]:$this->z01_numero);
       $this->z01_compl = ($this->z01_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_compl"]:$this->z01_compl);
       $this->z01_munic = ($this->z01_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_munic"]:$this->z01_munic);
       $this->z01_uf = ($this->z01_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_uf"]:$this->z01_uf);
       $this->z01_cep = ($this->z01_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cep"]:$this->z01_cep);
       $this->z01_cxpostal = ($this->z01_cxpostal == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cxpostal"]:$this->z01_cxpostal);
       if($this->z01_cadast == ""){
         $this->z01_cadast_dia = ($this->z01_cadast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cadast_dia"]:$this->z01_cadast_dia);
         $this->z01_cadast_mes = ($this->z01_cadast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cadast_mes"]:$this->z01_cadast_mes);
         $this->z01_cadast_ano = ($this->z01_cadast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cadast_ano"]:$this->z01_cadast_ano);
         if($this->z01_cadast_dia != ""){
            $this->z01_cadast = $this->z01_cadast_ano."-".$this->z01_cadast_mes."-".$this->z01_cadast_dia;
         }
       }
       $this->z01_telef = ($this->z01_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_telef"]:$this->z01_telef);
       $this->z01_ident = ($this->z01_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_ident"]:$this->z01_ident);
       $this->z01_login = ($this->z01_login == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_login"]:$this->z01_login);
       $this->z01_incest = ($this->z01_incest == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_incest"]:$this->z01_incest);
       $this->z01_telcel = ($this->z01_telcel == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_telcel"]:$this->z01_telcel);
       $this->z01_email = ($this->z01_email == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_email"]:$this->z01_email);
       $this->z01_endcon = ($this->z01_endcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_endcon"]:$this->z01_endcon);
       $this->z01_numcon = ($this->z01_numcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_numcon"]:$this->z01_numcon);
       $this->z01_comcon = ($this->z01_comcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_comcon"]:$this->z01_comcon);
       $this->z01_muncon = ($this->z01_muncon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_muncon"]:$this->z01_muncon);
       $this->z01_ufcon = ($this->z01_ufcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_ufcon"]:$this->z01_ufcon);
       $this->z01_cepcon = ($this->z01_cepcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cepcon"]:$this->z01_cepcon);
       $this->z01_cxposcon = ($this->z01_cxposcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cxposcon"]:$this->z01_cxposcon);
       $this->z01_telcon = ($this->z01_telcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_telcon"]:$this->z01_telcon);
       $this->z01_celcon = ($this->z01_celcon == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_celcon"]:$this->z01_celcon);
       $this->z01_emailc = ($this->z01_emailc == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_emailc"]:$this->z01_emailc);
       $this->z01_nacion = ($this->z01_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nacion"]:$this->z01_nacion);
       $this->z01_estciv = ($this->z01_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_estciv"]:$this->z01_estciv);
       $this->z01_profis = ($this->z01_profis == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_profis"]:$this->z01_profis);
       $this->z01_tipcre = ($this->z01_tipcre == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_tipcre"]:$this->z01_tipcre);
       $this->z01_cgccpf = ($this->z01_cgccpf == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cgccpf"]:$this->z01_cgccpf);
       $this->z01_fax = ($this->z01_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_fax"]:$this->z01_fax);
       if($this->z01_nasc == ""){
         $this->z01_nasc_dia = ($this->z01_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nasc_dia"]:$this->z01_nasc_dia);
         $this->z01_nasc_mes = ($this->z01_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nasc_mes"]:$this->z01_nasc_mes);
         $this->z01_nasc_ano = ($this->z01_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nasc_ano"]:$this->z01_nasc_ano);
         if($this->z01_nasc_dia != ""){
            $this->z01_nasc = $this->z01_nasc_ano."-".$this->z01_nasc_mes."-".$this->z01_nasc_dia;
         }
       }
       $this->z01_pai = ($this->z01_pai == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_pai"]:$this->z01_pai);
       $this->z01_mae = ($this->z01_mae == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_mae"]:$this->z01_mae);
       $this->z01_sexo = ($this->z01_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_sexo"]:$this->z01_sexo);
       if($this->z01_ultalt == ""){
         $this->z01_ultalt_dia = ($this->z01_ultalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_ultalt_dia"]:$this->z01_ultalt_dia);
         $this->z01_ultalt_mes = ($this->z01_ultalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_ultalt_mes"]:$this->z01_ultalt_mes);
         $this->z01_ultalt_ano = ($this->z01_ultalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_ultalt_ano"]:$this->z01_ultalt_ano);
         if($this->z01_ultalt_dia != ""){
            $this->z01_ultalt = $this->z01_ultalt_ano."-".$this->z01_ultalt_mes."-".$this->z01_ultalt_dia;
         }
       }
       $this->z01_contato = ($this->z01_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_contato"]:$this->z01_contato);
       $this->z01_hora = ($this->z01_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_hora"]:$this->z01_hora);
       $this->z01_nomefanta = ($this->z01_nomefanta == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nomefanta"]:$this->z01_nomefanta);
       $this->z01_cnh = ($this->z01_cnh == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cnh"]:$this->z01_cnh);
       $this->z01_categoria = ($this->z01_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_categoria"]:$this->z01_categoria);
       if($this->z01_dtemissao == ""){
         $this->z01_dtemissao_dia = ($this->z01_dtemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_dtemissao_dia"]:$this->z01_dtemissao_dia);
         $this->z01_dtemissao_mes = ($this->z01_dtemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_dtemissao_mes"]:$this->z01_dtemissao_mes);
         $this->z01_dtemissao_ano = ($this->z01_dtemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_dtemissao_ano"]:$this->z01_dtemissao_ano);
         if($this->z01_dtemissao_dia != ""){
            $this->z01_dtemissao = $this->z01_dtemissao_ano."-".$this->z01_dtemissao_mes."-".$this->z01_dtemissao_dia;
         }
       }
       $this->z01_nomecomple = ($this->z01_nomecomple == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nomecomple"]:$this->z01_nomecomple);
       if($this->z01_dtvencimento == ""){
         $this->z01_dtvencimento_dia = ($this->z01_dtvencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_dtvencimento_dia"]:$this->z01_dtvencimento_dia);
         $this->z01_dtvencimento_mes = ($this->z01_dtvencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_dtvencimento_mes"]:$this->z01_dtvencimento_mes);
         $this->z01_dtvencimento_ano = ($this->z01_dtvencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_dtvencimento_ano"]:$this->z01_dtvencimento_ano);
         if($this->z01_dtvencimento_dia != ""){
            $this->z01_dtvencimento = $this->z01_dtvencimento_ano."-".$this->z01_dtvencimento_mes."-".$this->z01_dtvencimento_dia;
         }
       }
       $this->z01_situacao = ($this->z01_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_situacao"]:$this->z01_situacao);
     }else{
       $this->z01_sequencial = ($this->z01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_sequencial"]:$this->z01_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($z01_sequencial){ 
      $this->atualizacampos();
     if($this->z01_nome == null ){ 
       $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
       $this->erro_campo = "z01_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "z01_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_numero == null ){ 
       $this->z01_numero = "0";
     }
     if($this->z01_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "z01_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_cadast == null ){ 
       $this->z01_cadast = "null";
     }
     if($this->z01_login == null ){ 
       $this->z01_login = "0";
     }
     if($this->z01_numcon == null ){ 
       $this->z01_numcon = "0";
     }
     if($this->z01_nacion == null ){ 
       $this->z01_nacion = "0";
     }
     if($this->z01_estciv == null ){ 
       $this->z01_estciv = "0";
     }
     if($this->z01_tipcre == null ){ 
       $this->z01_tipcre = "0";
     }
     if($this->z01_nasc == null ){ 
       $this->z01_nasc = "null";
     }
     if($this->z01_ultalt == null ){ 
       $this->z01_ultalt = "null";
     }
     if($this->z01_dtemissao == null ){ 
       $this->z01_dtemissao = "null";
     }
     if($this->z01_dtvencimento == null ){ 
       $this->z01_dtvencimento = "null";
     }
     if($this->z01_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "z01_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($z01_sequencial == "" || $z01_sequencial == null ){
       $result = db_query("select nextval('dbprefcgm_z01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dbprefcgm_z01_sequencial_seq do campo: z01_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from dbprefcgm_z01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $z01_sequencial)){
         $this->erro_sql = " Campo z01_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z01_sequencial = $z01_sequencial; 
       }
     }
     if(($this->z01_sequencial == null) || ($this->z01_sequencial == "") ){ 
       $this->erro_sql = " Campo z01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dbprefcgm(
                                       z01_sequencial 
                                      ,z01_baicon 
                                      ,z01_bairro 
                                      ,z01_nome 
                                      ,z01_ender 
                                      ,z01_numero 
                                      ,z01_compl 
                                      ,z01_munic 
                                      ,z01_uf 
                                      ,z01_cep 
                                      ,z01_cxpostal 
                                      ,z01_cadast 
                                      ,z01_telef 
                                      ,z01_ident 
                                      ,z01_login 
                                      ,z01_incest 
                                      ,z01_telcel 
                                      ,z01_email 
                                      ,z01_endcon 
                                      ,z01_numcon 
                                      ,z01_comcon 
                                      ,z01_muncon 
                                      ,z01_ufcon 
                                      ,z01_cepcon 
                                      ,z01_cxposcon 
                                      ,z01_telcon 
                                      ,z01_celcon 
                                      ,z01_emailc 
                                      ,z01_nacion 
                                      ,z01_estciv 
                                      ,z01_profis 
                                      ,z01_tipcre 
                                      ,z01_cgccpf 
                                      ,z01_fax 
                                      ,z01_nasc 
                                      ,z01_pai 
                                      ,z01_mae 
                                      ,z01_sexo 
                                      ,z01_ultalt 
                                      ,z01_contato 
                                      ,z01_hora 
                                      ,z01_nomefanta 
                                      ,z01_cnh 
                                      ,z01_categoria 
                                      ,z01_dtemissao 
                                      ,z01_nomecomple 
                                      ,z01_dtvencimento 
                                      ,z01_situacao 
                       )
                values (
                                $this->z01_sequencial 
                               ,'$this->z01_baicon' 
                               ,'$this->z01_bairro' 
                               ,'$this->z01_nome' 
                               ,'$this->z01_ender' 
                               ,$this->z01_numero 
                               ,'$this->z01_compl' 
                               ,'$this->z01_munic' 
                               ,'$this->z01_uf' 
                               ,'$this->z01_cep' 
                               ,'$this->z01_cxpostal' 
                               ,".($this->z01_cadast == "null" || $this->z01_cadast == ""?"null":"'".$this->z01_cadast."'")." 
                               ,'$this->z01_telef' 
                               ,'$this->z01_ident' 
                               ,$this->z01_login 
                               ,'$this->z01_incest' 
                               ,'$this->z01_telcel' 
                               ,'$this->z01_email' 
                               ,'$this->z01_endcon' 
                               ,$this->z01_numcon 
                               ,'$this->z01_comcon' 
                               ,'$this->z01_muncon' 
                               ,'$this->z01_ufcon' 
                               ,'$this->z01_cepcon' 
                               ,'$this->z01_cxposcon' 
                               ,'$this->z01_telcon' 
                               ,'$this->z01_celcon' 
                               ,'$this->z01_emailc' 
                               ,$this->z01_nacion 
                               ,$this->z01_estciv 
                               ,'$this->z01_profis' 
                               ,$this->z01_tipcre 
                               ,'$this->z01_cgccpf' 
                               ,'$this->z01_fax' 
                               ,".($this->z01_nasc == "null" || $this->z01_nasc == ""?"null":"'".$this->z01_nasc."'")." 
                               ,'$this->z01_pai' 
                               ,'$this->z01_mae' 
                               ,'$this->z01_sexo' 
                               ,".($this->z01_ultalt == "null" || $this->z01_ultalt == ""?"null":"'".$this->z01_ultalt."'")." 
                               ,'$this->z01_contato' 
                               ,'$this->z01_hora' 
                               ,'$this->z01_nomefanta' 
                               ,'$this->z01_cnh' 
                               ,'$this->z01_categoria' 
                               ,".($this->z01_dtemissao == "null" || $this->z01_dtemissao == ""?"null":"'".$this->z01_dtemissao."'")." 
                               ,'$this->z01_nomecomple' 
                               ,".($this->z01_dtvencimento == "null" || $this->z01_dtvencimento == ""?"null":"'".$this->z01_dtvencimento."'")." 
                               ,$this->z01_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dbprefcgm ($this->z01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "dbprefcgm já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "dbprefcgm ($this->z01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z01_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z01_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10200,'$this->z01_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1758,10200,'','".AddSlashes(pg_result($resaco,0,'z01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,233,'','".AddSlashes(pg_result($resaco,0,'z01_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,227,'','".AddSlashes(pg_result($resaco,0,'z01_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,217,'','".AddSlashes(pg_result($resaco,0,'z01_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,218,'','".AddSlashes(pg_result($resaco,0,'z01_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,732,'','".AddSlashes(pg_result($resaco,0,'z01_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,733,'','".AddSlashes(pg_result($resaco,0,'z01_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,219,'','".AddSlashes(pg_result($resaco,0,'z01_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,220,'','".AddSlashes(pg_result($resaco,0,'z01_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,221,'','".AddSlashes(pg_result($resaco,0,'z01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,738,'','".AddSlashes(pg_result($resaco,0,'z01_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,222,'','".AddSlashes(pg_result($resaco,0,'z01_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,223,'','".AddSlashes(pg_result($resaco,0,'z01_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,224,'','".AddSlashes(pg_result($resaco,0,'z01_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,226,'','".AddSlashes(pg_result($resaco,0,'z01_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,228,'','".AddSlashes(pg_result($resaco,0,'z01_incest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,229,'','".AddSlashes(pg_result($resaco,0,'z01_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,230,'','".AddSlashes(pg_result($resaco,0,'z01_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,231,'','".AddSlashes(pg_result($resaco,0,'z01_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,734,'','".AddSlashes(pg_result($resaco,0,'z01_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,735,'','".AddSlashes(pg_result($resaco,0,'z01_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,232,'','".AddSlashes(pg_result($resaco,0,'z01_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,234,'','".AddSlashes(pg_result($resaco,0,'z01_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,235,'','".AddSlashes(pg_result($resaco,0,'z01_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,739,'','".AddSlashes(pg_result($resaco,0,'z01_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,236,'','".AddSlashes(pg_result($resaco,0,'z01_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,237,'','".AddSlashes(pg_result($resaco,0,'z01_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,238,'','".AddSlashes(pg_result($resaco,0,'z01_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,239,'','".AddSlashes(pg_result($resaco,0,'z01_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,240,'','".AddSlashes(pg_result($resaco,0,'z01_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,241,'','".AddSlashes(pg_result($resaco,0,'z01_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,242,'','".AddSlashes(pg_result($resaco,0,'z01_tipcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,1126,'','".AddSlashes(pg_result($resaco,0,'z01_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6736,'','".AddSlashes(pg_result($resaco,0,'z01_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6737,'','".AddSlashes(pg_result($resaco,0,'z01_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6738,'','".AddSlashes(pg_result($resaco,0,'z01_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6739,'','".AddSlashes(pg_result($resaco,0,'z01_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6740,'','".AddSlashes(pg_result($resaco,0,'z01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6741,'','".AddSlashes(pg_result($resaco,0,'z01_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6742,'','".AddSlashes(pg_result($resaco,0,'z01_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6743,'','".AddSlashes(pg_result($resaco,0,'z01_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6749,'','".AddSlashes(pg_result($resaco,0,'z01_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7294,'','".AddSlashes(pg_result($resaco,0,'z01_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7295,'','".AddSlashes(pg_result($resaco,0,'z01_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7296,'','".AddSlashes(pg_result($resaco,0,'z01_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7309,'','".AddSlashes(pg_result($resaco,0,'z01_nomecomple'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7344,'','".AddSlashes(pg_result($resaco,0,'z01_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,10202,'','".AddSlashes(pg_result($resaco,0,'z01_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcção para incluir com campos null
function incluir_dbpref ($z01_sequencial){ 
      $this->atualizacampos();
     if($this->z01_nome == null ){ 
       $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
       $this->erro_campo = "z01_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if($this->z01_ender == null ){ 
        $this->z01_ender = "null";
     }
     if($this->z01_numero == null ){ 
       $this->z01_numero = "0";
     }
     if($this->z01_cep == null ){ 
       $this->z01_cep = "null";
     }
     if($this->z01_cadast == null ){ 
       $this->z01_cadast = "null";
     }
     if($this->z01_login == null ){ 
       $this->z01_login = "0";
     }
     if($this->z01_numcon == null ){ 
       $this->z01_numcon = "0";
     }
     if($this->z01_nacion == null ){ 
       $this->z01_nacion = "0";
     }
     if($this->z01_estciv == null ){ 
       $this->z01_estciv = "0";
     }
     if($this->z01_tipcre == null ){ 
       $this->z01_tipcre = "0";
     }
     if($this->z01_nasc == null ){ 
       $this->z01_nasc = "null";
     }
     if($this->z01_ultalt == null ){ 
       $this->z01_ultalt = "null";
     }
     if($this->z01_dtemissao == null ){ 
       $this->z01_dtemissao = "null";
     }
     if($this->z01_dtvencimento == null ){ 
       $this->z01_dtvencimento = "null";
     }
     if($this->z01_situacao == null ){ 
       $this->z01_situacao = "null";
     }
     if($z01_sequencial == "" || $z01_sequencial == null ){
       $result = @db_query("select nextval('dbprefcgm_z01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dbprefcgm_z01_sequencial_seq do campo: z01_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z01_sequencial = pg_result($result,0,0); 
     }else{
       $result = @db_query("select last_value from dbprefcgm_z01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $z01_sequencial)){
         $this->erro_sql = " Campo z01_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z01_sequencial = $z01_sequencial; 
       }
     }
     if(($this->z01_sequencial == null) || ($this->z01_sequencial == "") ){ 
       $this->erro_sql = " Campo z01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dbprefcgm(
                                       z01_sequencial 
                                      ,z01_baicon 
                                      ,z01_bairro 
                                      ,z01_nome 
                                      ,z01_ender 
                                      ,z01_numero 
                                      ,z01_compl 
                                      ,z01_munic 
                                      ,z01_uf 
                                      ,z01_cep 
                                      ,z01_cxpostal 
                                      ,z01_cadast 
                                      ,z01_telef 
                                      ,z01_ident 
                                      ,z01_login 
                                      ,z01_incest 
                                      ,z01_telcel 
                                      ,z01_email 
                                      ,z01_endcon 
                                      ,z01_numcon 
                                      ,z01_comcon 
                                      ,z01_muncon 
                                      ,z01_ufcon 
                                      ,z01_cepcon 
                                      ,z01_cxposcon 
                                      ,z01_telcon 
                                      ,z01_celcon 
                                      ,z01_emailc 
                                      ,z01_nacion 
                                      ,z01_estciv 
                                      ,z01_profis 
                                      ,z01_tipcre 
                                      ,z01_cgccpf 
                                      ,z01_fax 
                                      ,z01_nasc 
                                      ,z01_pai 
                                      ,z01_mae 
                                      ,z01_sexo 
                                      ,z01_ultalt 
                                      ,z01_contato 
                                      ,z01_hora 
                                      ,z01_nomefanta 
                                      ,z01_cnh 
                                      ,z01_categoria 
                                      ,z01_dtemissao 
                                      ,z01_nomecomple 
                                      ,z01_dtvencimento 
                                      ,z01_situacao 
                       )
                values (
                                $this->z01_sequencial 
                               ,'$this->z01_baicon' 
                               ,'$this->z01_bairro' 
                               ,'$this->z01_nome' 
                               ,'$this->z01_ender' 
                               ,$this->z01_numero 
                               ,'$this->z01_compl' 
                               ,'$this->z01_munic' 
                               ,'$this->z01_uf' 
                               ,'$this->z01_cep' 
                               ,'$this->z01_cxpostal' 
                               ,".($this->z01_cadast == "null" || $this->z01_cadast == ""?"null":"'".$this->z01_cadast."'")." 
                               ,'$this->z01_telef' 
                               ,'$this->z01_ident' 
                               ,$this->z01_login 
                               ,'$this->z01_incest' 
                               ,'$this->z01_telcel' 
                               ,'$this->z01_email' 
                               ,'$this->z01_endcon' 
                               ,$this->z01_numcon 
                               ,'$this->z01_comcon' 
                               ,'$this->z01_muncon' 
                               ,'$this->z01_ufcon' 
                               ,'$this->z01_cepcon' 
                               ,'$this->z01_cxposcon' 
                               ,'$this->z01_telcon' 
                               ,'$this->z01_celcon' 
                               ,'$this->z01_emailc' 
                               ,$this->z01_nacion 
                               ,$this->z01_estciv 
                               ,'$this->z01_profis' 
                               ,$this->z01_tipcre 
                               ,'$this->z01_cgccpf' 
                               ,'$this->z01_fax' 
                               ,".($this->z01_nasc == "null" || $this->z01_nasc == ""?"null":"'".$this->z01_nasc."'")." 
                               ,'$this->z01_pai' 
                               ,'$this->z01_mae' 
                               ,'$this->z01_sexo' 
                               ,".($this->z01_ultalt == "null" || $this->z01_ultalt == ""?"null":"'".$this->z01_ultalt."'")." 
                               ,'$this->z01_contato' 
                               ,'$this->z01_hora' 
                               ,'$this->z01_nomefanta' 
                               ,'$this->z01_cnh' 
                               ,'$this->z01_categoria' 
                               ,".($this->z01_dtemissao == "null" || $this->z01_dtemissao == ""?"null":"'".$this->z01_dtemissao."'")." 
                               ,'$this->z01_nomecomple' 
                               ,".($this->z01_dtvencimento == "null" || $this->z01_dtvencimento == ""?"null":"'".$this->z01_dtvencimento."'")." 
                               ,$this->z01_situacao 
                      )";
    // echo"<br>$sql <br>";
     $result = @db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dbprefcgm ($this->z01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "dbprefcgm já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "dbprefcgm ($this->z01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z01_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z01_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10200,'$this->z01_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1758,10200,'','".AddSlashes(pg_result($resaco,0,'z01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,233,'','".AddSlashes(pg_result($resaco,0,'z01_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,227,'','".AddSlashes(pg_result($resaco,0,'z01_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,217,'','".AddSlashes(pg_result($resaco,0,'z01_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,218,'','".AddSlashes(pg_result($resaco,0,'z01_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,732,'','".AddSlashes(pg_result($resaco,0,'z01_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,733,'','".AddSlashes(pg_result($resaco,0,'z01_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,219,'','".AddSlashes(pg_result($resaco,0,'z01_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,220,'','".AddSlashes(pg_result($resaco,0,'z01_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,221,'','".AddSlashes(pg_result($resaco,0,'z01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,738,'','".AddSlashes(pg_result($resaco,0,'z01_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,222,'','".AddSlashes(pg_result($resaco,0,'z01_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,223,'','".AddSlashes(pg_result($resaco,0,'z01_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,224,'','".AddSlashes(pg_result($resaco,0,'z01_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,226,'','".AddSlashes(pg_result($resaco,0,'z01_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,228,'','".AddSlashes(pg_result($resaco,0,'z01_incest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,229,'','".AddSlashes(pg_result($resaco,0,'z01_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,230,'','".AddSlashes(pg_result($resaco,0,'z01_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,231,'','".AddSlashes(pg_result($resaco,0,'z01_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,734,'','".AddSlashes(pg_result($resaco,0,'z01_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,735,'','".AddSlashes(pg_result($resaco,0,'z01_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,232,'','".AddSlashes(pg_result($resaco,0,'z01_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,234,'','".AddSlashes(pg_result($resaco,0,'z01_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,235,'','".AddSlashes(pg_result($resaco,0,'z01_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,739,'','".AddSlashes(pg_result($resaco,0,'z01_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,236,'','".AddSlashes(pg_result($resaco,0,'z01_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,237,'','".AddSlashes(pg_result($resaco,0,'z01_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,238,'','".AddSlashes(pg_result($resaco,0,'z01_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,239,'','".AddSlashes(pg_result($resaco,0,'z01_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,240,'','".AddSlashes(pg_result($resaco,0,'z01_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,241,'','".AddSlashes(pg_result($resaco,0,'z01_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,242,'','".AddSlashes(pg_result($resaco,0,'z01_tipcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,1126,'','".AddSlashes(pg_result($resaco,0,'z01_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6736,'','".AddSlashes(pg_result($resaco,0,'z01_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6737,'','".AddSlashes(pg_result($resaco,0,'z01_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6738,'','".AddSlashes(pg_result($resaco,0,'z01_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6739,'','".AddSlashes(pg_result($resaco,0,'z01_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6740,'','".AddSlashes(pg_result($resaco,0,'z01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6741,'','".AddSlashes(pg_result($resaco,0,'z01_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6742,'','".AddSlashes(pg_result($resaco,0,'z01_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6743,'','".AddSlashes(pg_result($resaco,0,'z01_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,6749,'','".AddSlashes(pg_result($resaco,0,'z01_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7294,'','".AddSlashes(pg_result($resaco,0,'z01_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7295,'','".AddSlashes(pg_result($resaco,0,'z01_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7296,'','".AddSlashes(pg_result($resaco,0,'z01_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7309,'','".AddSlashes(pg_result($resaco,0,'z01_nomecomple'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,7344,'','".AddSlashes(pg_result($resaco,0,'z01_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1758,10202,'','".AddSlashes(pg_result($resaco,0,'z01_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update dbprefcgm set ";
     $virgula = "";
     if(trim($this->z01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_sequencial"])){ 
       $sql  .= $virgula." z01_sequencial = $this->z01_sequencial ";
       $virgula = ",";
       if(trim($this->z01_sequencial) == null ){ 
         $this->erro_sql = " Campo Código sequencial nao Informado.";
         $this->erro_campo = "z01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_baicon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_baicon"])){ 
       $sql  .= $virgula." z01_baicon = '$this->z01_baicon' ";
       $virgula = ",";
     }
     if(trim($this->z01_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_bairro"])){ 
       $sql  .= $virgula." z01_bairro = '$this->z01_bairro' ";
       $virgula = ",";
     }
     if(trim($this->z01_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_nome"])){ 
       $sql  .= $virgula." z01_nome = '$this->z01_nome' ";
       $virgula = ",";
       if(trim($this->z01_nome) == null ){ 
         $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
         $this->erro_campo = "z01_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_ender"])){ 
       $sql  .= $virgula." z01_ender = '$this->z01_ender' ";
       $virgula = ",";
       if(trim($this->z01_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "z01_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_numero"])){ 
        if(trim($this->z01_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_numero"])){ 
           $this->z01_numero = "0" ; 
        } 
       $sql  .= $virgula." z01_numero = $this->z01_numero ";
       $virgula = ",";
     }
     if(trim($this->z01_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_compl"])){ 
       $sql  .= $virgula." z01_compl = '$this->z01_compl' ";
       $virgula = ",";
     }
     if(trim($this->z01_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_munic"])){ 
       $sql  .= $virgula." z01_munic = '$this->z01_munic' ";
       $virgula = ",";
     }
     if(trim($this->z01_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_uf"])){ 
       $sql  .= $virgula." z01_uf = '$this->z01_uf' ";
       $virgula = ",";
     }
     if(trim($this->z01_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cep"])){ 
       $sql  .= $virgula." z01_cep = '$this->z01_cep' ";
       $virgula = ",";
       if(trim($this->z01_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "z01_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_cxpostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cxpostal"])){ 
       $sql  .= $virgula." z01_cxpostal = '$this->z01_cxpostal' ";
       $virgula = ",";
     }
     if(trim($this->z01_cadast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cadast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_cadast_dia"] !="") ){ 
       $sql  .= $virgula." z01_cadast = '$this->z01_cadast' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cadast_dia"])){ 
         $sql  .= $virgula." z01_cadast = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_telef"])){ 
       $sql  .= $virgula." z01_telef = '$this->z01_telef' ";
       $virgula = ",";
     }
     if(trim($this->z01_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_ident"])){ 
       $sql  .= $virgula." z01_ident = '$this->z01_ident' ";
       $virgula = ",";
     }
     if(trim($this->z01_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_login"])){ 
        if(trim($this->z01_login)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_login"])){ 
           $this->z01_login = "0" ; 
        } 
       $sql  .= $virgula." z01_login = $this->z01_login ";
       $virgula = ",";
     }
     if(trim($this->z01_incest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_incest"])){ 
       $sql  .= $virgula." z01_incest = '$this->z01_incest' ";
       $virgula = ",";
     }
     if(trim($this->z01_telcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_telcel"])){ 
       $sql  .= $virgula." z01_telcel = '$this->z01_telcel' ";
       $virgula = ",";
     }
     if(trim($this->z01_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_email"])){ 
       $sql  .= $virgula." z01_email = '$this->z01_email' ";
       $virgula = ",";
     }
     if(trim($this->z01_endcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_endcon"])){ 
       $sql  .= $virgula." z01_endcon = '$this->z01_endcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_numcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_numcon"])){ 
        if(trim($this->z01_numcon)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_numcon"])){ 
           $this->z01_numcon = "0" ; 
        } 
       $sql  .= $virgula." z01_numcon = $this->z01_numcon ";
       $virgula = ",";
     }
     if(trim($this->z01_comcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_comcon"])){ 
       $sql  .= $virgula." z01_comcon = '$this->z01_comcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_muncon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_muncon"])){ 
       $sql  .= $virgula." z01_muncon = '$this->z01_muncon' ";
       $virgula = ",";
     }
     if(trim($this->z01_ufcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_ufcon"])){ 
       $sql  .= $virgula." z01_ufcon = '$this->z01_ufcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_cepcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cepcon"])){ 
       $sql  .= $virgula." z01_cepcon = '$this->z01_cepcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_cxposcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cxposcon"])){ 
       $sql  .= $virgula." z01_cxposcon = '$this->z01_cxposcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_telcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_telcon"])){ 
       $sql  .= $virgula." z01_telcon = '$this->z01_telcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_celcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_celcon"])){ 
       $sql  .= $virgula." z01_celcon = '$this->z01_celcon' ";
       $virgula = ",";
     }
     if(trim($this->z01_emailc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_emailc"])){ 
       $sql  .= $virgula." z01_emailc = '$this->z01_emailc' ";
       $virgula = ",";
     }
     if(trim($this->z01_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_nacion"])){ 
        if(trim($this->z01_nacion)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_nacion"])){ 
           $this->z01_nacion = "0" ; 
        } 
       $sql  .= $virgula." z01_nacion = $this->z01_nacion ";
       $virgula = ",";
     }
     if(trim($this->z01_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_estciv"])){ 
        if(trim($this->z01_estciv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_estciv"])){ 
           $this->z01_estciv = "0" ; 
        } 
       $sql  .= $virgula." z01_estciv = $this->z01_estciv ";
       $virgula = ",";
     }
     if(trim($this->z01_profis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_profis"])){ 
       $sql  .= $virgula." z01_profis = '$this->z01_profis' ";
       $virgula = ",";
     }
     if(trim($this->z01_tipcre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_tipcre"])){ 
        if(trim($this->z01_tipcre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_tipcre"])){ 
           $this->z01_tipcre = "0" ; 
        } 
       $sql  .= $virgula." z01_tipcre = $this->z01_tipcre ";
       $virgula = ",";
     }
     if(trim($this->z01_cgccpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cgccpf"])){ 
       $sql  .= $virgula." z01_cgccpf = '$this->z01_cgccpf' ";
       $virgula = ",";
     }
     if(trim($this->z01_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_fax"])){ 
       $sql  .= $virgula." z01_fax = '$this->z01_fax' ";
       $virgula = ",";
     }
     if(trim($this->z01_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_nasc_dia"] !="") ){ 
       $sql  .= $virgula." z01_nasc = '$this->z01_nasc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_nasc_dia"])){ 
         $sql  .= $virgula." z01_nasc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_pai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_pai"])){ 
       $sql  .= $virgula." z01_pai = '$this->z01_pai' ";
       $virgula = ",";
     }
     if(trim($this->z01_mae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_mae"])){ 
       $sql  .= $virgula." z01_mae = '$this->z01_mae' ";
       $virgula = ",";
     }
     if(trim($this->z01_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_sexo"])){ 
       $sql  .= $virgula." z01_sexo = '$this->z01_sexo' ";
       $virgula = ",";
     }
     if(trim($this->z01_ultalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_ultalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_ultalt_dia"] !="") ){ 
       $sql  .= $virgula." z01_ultalt = '$this->z01_ultalt' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_ultalt_dia"])){ 
         $sql  .= $virgula." z01_ultalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_contato"])){ 
       $sql  .= $virgula." z01_contato = '$this->z01_contato' ";
       $virgula = ",";
     }
     if(trim($this->z01_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_hora"])){ 
       $sql  .= $virgula." z01_hora = '$this->z01_hora' ";
       $virgula = ",";
     }
     if(trim($this->z01_nomefanta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_nomefanta"])){ 
       $sql  .= $virgula." z01_nomefanta = '$this->z01_nomefanta' ";
       $virgula = ",";
     }
     if(trim($this->z01_cnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cnh"])){ 
       $sql  .= $virgula." z01_cnh = '$this->z01_cnh' ";
       $virgula = ",";
     }
     if(trim($this->z01_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_categoria"])){ 
       $sql  .= $virgula." z01_categoria = '$this->z01_categoria' ";
       $virgula = ",";
     }
     if(trim($this->z01_dtemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_dtemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_dtemissao_dia"] !="") ){ 
       $sql  .= $virgula." z01_dtemissao = '$this->z01_dtemissao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_dtemissao_dia"])){ 
         $sql  .= $virgula." z01_dtemissao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_nomecomple)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_nomecomple"])){ 
       $sql  .= $virgula." z01_nomecomple = '$this->z01_nomecomple' ";
       $virgula = ",";
     }
     if(trim($this->z01_dtvencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_dtvencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z01_dtvencimento_dia"] !="") ){ 
       $sql  .= $virgula." z01_dtvencimento = '$this->z01_dtvencimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_dtvencimento_dia"])){ 
         $sql  .= $virgula." z01_dtvencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z01_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_situacao"])){ 
       $sql  .= $virgula." z01_situacao = $this->z01_situacao ";
       $virgula = ",";
       if(trim($this->z01_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "z01_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z01_sequencial!=null){
       $sql .= " z01_sequencial = $this->z01_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z01_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10200,'$this->z01_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1758,10200,'".AddSlashes(pg_result($resaco,$conresaco,'z01_sequencial'))."','$this->z01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_baicon"]))
           $resac = db_query("insert into db_acount values($acount,1758,233,'".AddSlashes(pg_result($resaco,$conresaco,'z01_baicon'))."','$this->z01_baicon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_bairro"]))
           $resac = db_query("insert into db_acount values($acount,1758,227,'".AddSlashes(pg_result($resaco,$conresaco,'z01_bairro'))."','$this->z01_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_nome"]))
           $resac = db_query("insert into db_acount values($acount,1758,217,'".AddSlashes(pg_result($resaco,$conresaco,'z01_nome'))."','$this->z01_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_ender"]))
           $resac = db_query("insert into db_acount values($acount,1758,218,'".AddSlashes(pg_result($resaco,$conresaco,'z01_ender'))."','$this->z01_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_numero"]))
           $resac = db_query("insert into db_acount values($acount,1758,732,'".AddSlashes(pg_result($resaco,$conresaco,'z01_numero'))."','$this->z01_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_compl"]))
           $resac = db_query("insert into db_acount values($acount,1758,733,'".AddSlashes(pg_result($resaco,$conresaco,'z01_compl'))."','$this->z01_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_munic"]))
           $resac = db_query("insert into db_acount values($acount,1758,219,'".AddSlashes(pg_result($resaco,$conresaco,'z01_munic'))."','$this->z01_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_uf"]))
           $resac = db_query("insert into db_acount values($acount,1758,220,'".AddSlashes(pg_result($resaco,$conresaco,'z01_uf'))."','$this->z01_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cep"]))
           $resac = db_query("insert into db_acount values($acount,1758,221,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cep'))."','$this->z01_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cxpostal"]))
           $resac = db_query("insert into db_acount values($acount,1758,738,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cxpostal'))."','$this->z01_cxpostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cadast"]))
           $resac = db_query("insert into db_acount values($acount,1758,222,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cadast'))."','$this->z01_cadast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_telef"]))
           $resac = db_query("insert into db_acount values($acount,1758,223,'".AddSlashes(pg_result($resaco,$conresaco,'z01_telef'))."','$this->z01_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_ident"]))
           $resac = db_query("insert into db_acount values($acount,1758,224,'".AddSlashes(pg_result($resaco,$conresaco,'z01_ident'))."','$this->z01_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_login"]))
           $resac = db_query("insert into db_acount values($acount,1758,226,'".AddSlashes(pg_result($resaco,$conresaco,'z01_login'))."','$this->z01_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_incest"]))
           $resac = db_query("insert into db_acount values($acount,1758,228,'".AddSlashes(pg_result($resaco,$conresaco,'z01_incest'))."','$this->z01_incest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_telcel"]))
           $resac = db_query("insert into db_acount values($acount,1758,229,'".AddSlashes(pg_result($resaco,$conresaco,'z01_telcel'))."','$this->z01_telcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_email"]))
           $resac = db_query("insert into db_acount values($acount,1758,230,'".AddSlashes(pg_result($resaco,$conresaco,'z01_email'))."','$this->z01_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_endcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,231,'".AddSlashes(pg_result($resaco,$conresaco,'z01_endcon'))."','$this->z01_endcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_numcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,734,'".AddSlashes(pg_result($resaco,$conresaco,'z01_numcon'))."','$this->z01_numcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_comcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,735,'".AddSlashes(pg_result($resaco,$conresaco,'z01_comcon'))."','$this->z01_comcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_muncon"]))
           $resac = db_query("insert into db_acount values($acount,1758,232,'".AddSlashes(pg_result($resaco,$conresaco,'z01_muncon'))."','$this->z01_muncon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_ufcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,234,'".AddSlashes(pg_result($resaco,$conresaco,'z01_ufcon'))."','$this->z01_ufcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cepcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,235,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cepcon'))."','$this->z01_cepcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cxposcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,739,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cxposcon'))."','$this->z01_cxposcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_telcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,236,'".AddSlashes(pg_result($resaco,$conresaco,'z01_telcon'))."','$this->z01_telcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_celcon"]))
           $resac = db_query("insert into db_acount values($acount,1758,237,'".AddSlashes(pg_result($resaco,$conresaco,'z01_celcon'))."','$this->z01_celcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_emailc"]))
           $resac = db_query("insert into db_acount values($acount,1758,238,'".AddSlashes(pg_result($resaco,$conresaco,'z01_emailc'))."','$this->z01_emailc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_nacion"]))
           $resac = db_query("insert into db_acount values($acount,1758,239,'".AddSlashes(pg_result($resaco,$conresaco,'z01_nacion'))."','$this->z01_nacion',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_estciv"]))
           $resac = db_query("insert into db_acount values($acount,1758,240,'".AddSlashes(pg_result($resaco,$conresaco,'z01_estciv'))."','$this->z01_estciv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_profis"]))
           $resac = db_query("insert into db_acount values($acount,1758,241,'".AddSlashes(pg_result($resaco,$conresaco,'z01_profis'))."','$this->z01_profis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_tipcre"]))
           $resac = db_query("insert into db_acount values($acount,1758,242,'".AddSlashes(pg_result($resaco,$conresaco,'z01_tipcre'))."','$this->z01_tipcre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cgccpf"]))
           $resac = db_query("insert into db_acount values($acount,1758,1126,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cgccpf'))."','$this->z01_cgccpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_fax"]))
           $resac = db_query("insert into db_acount values($acount,1758,6736,'".AddSlashes(pg_result($resaco,$conresaco,'z01_fax'))."','$this->z01_fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_nasc"]))
           $resac = db_query("insert into db_acount values($acount,1758,6737,'".AddSlashes(pg_result($resaco,$conresaco,'z01_nasc'))."','$this->z01_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_pai"]))
           $resac = db_query("insert into db_acount values($acount,1758,6738,'".AddSlashes(pg_result($resaco,$conresaco,'z01_pai'))."','$this->z01_pai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_mae"]))
           $resac = db_query("insert into db_acount values($acount,1758,6739,'".AddSlashes(pg_result($resaco,$conresaco,'z01_mae'))."','$this->z01_mae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_sexo"]))
           $resac = db_query("insert into db_acount values($acount,1758,6740,'".AddSlashes(pg_result($resaco,$conresaco,'z01_sexo'))."','$this->z01_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_ultalt"]))
           $resac = db_query("insert into db_acount values($acount,1758,6741,'".AddSlashes(pg_result($resaco,$conresaco,'z01_ultalt'))."','$this->z01_ultalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_contato"]))
           $resac = db_query("insert into db_acount values($acount,1758,6742,'".AddSlashes(pg_result($resaco,$conresaco,'z01_contato'))."','$this->z01_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_hora"]))
           $resac = db_query("insert into db_acount values($acount,1758,6743,'".AddSlashes(pg_result($resaco,$conresaco,'z01_hora'))."','$this->z01_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_nomefanta"]))
           $resac = db_query("insert into db_acount values($acount,1758,6749,'".AddSlashes(pg_result($resaco,$conresaco,'z01_nomefanta'))."','$this->z01_nomefanta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cnh"]))
           $resac = db_query("insert into db_acount values($acount,1758,7294,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cnh'))."','$this->z01_cnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_categoria"]))
           $resac = db_query("insert into db_acount values($acount,1758,7295,'".AddSlashes(pg_result($resaco,$conresaco,'z01_categoria'))."','$this->z01_categoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_dtemissao"]))
           $resac = db_query("insert into db_acount values($acount,1758,7296,'".AddSlashes(pg_result($resaco,$conresaco,'z01_dtemissao'))."','$this->z01_dtemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_nomecomple"]))
           $resac = db_query("insert into db_acount values($acount,1758,7309,'".AddSlashes(pg_result($resaco,$conresaco,'z01_nomecomple'))."','$this->z01_nomecomple',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_dtvencimento"]))
           $resac = db_query("insert into db_acount values($acount,1758,7344,'".AddSlashes(pg_result($resaco,$conresaco,'z01_dtvencimento'))."','$this->z01_dtvencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1758,10202,'".AddSlashes(pg_result($resaco,$conresaco,'z01_situacao'))."','$this->z01_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dbprefcgm nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dbprefcgm nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z01_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z01_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10200,'$z01_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1758,10200,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,233,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_baicon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,227,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,217,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,218,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,732,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,733,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,219,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,220,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,221,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,738,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cxpostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,222,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,223,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,224,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,226,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,228,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_incest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,229,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,230,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,231,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_endcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,734,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_numcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,735,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_comcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,232,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_muncon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,234,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_ufcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,235,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cepcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,739,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cxposcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,236,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_telcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,237,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_celcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,238,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_emailc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,239,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_nacion'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,240,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_estciv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,241,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_profis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,242,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_tipcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,1126,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6736,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6737,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6738,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_pai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6739,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_mae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6740,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6741,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_ultalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6742,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6743,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,6749,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,7294,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,7295,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,7296,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,7309,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_nomecomple'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,7344,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1758,10202,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from dbprefcgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z01_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z01_sequencial = $z01_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dbprefcgm nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dbprefcgm nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z01_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dbprefcgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $z01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbprefcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($z01_sequencial!=null ){
         $sql2 .= " where dbprefcgm.z01_sequencial = $z01_sequencial "; 
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
   function sql_query_file ( $z01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbprefcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($z01_sequencial!=null ){
         $sql2 .= " where dbprefcgm.z01_sequencial = $z01_sequencial "; 
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
