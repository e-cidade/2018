<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: licitação
//CLASSE DA ENTIDADE licbaixa
class cl_licbaixa { 
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
   var $l28_sequencial = 0; 
   var $l28_nome = null; 
   var $l28_cnpj = null; 
   var $l28_email = null; 
   var $l28_endereco = null; 
   var $l28_cidade = null; 
   var $l28_fone = null; 
   var $l28_data_dia = null; 
   var $l28_data_mes = null; 
   var $l28_data_ano = null; 
   var $l28_data = null; 
   var $l28_hora = null; 
   var $l28_ip = null; 
   var $l28_liclicita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l28_sequencial = int4 = sequencial 
                 l28_nome = varchar(60) = Nome 
                 l28_cnpj = varchar(14) = CNPJ/CPF 
                 l28_email = varchar(40) = Email 
                 l28_endereco = varchar(60) = Endereço 
                 l28_cidade = varchar(30) = Cidade 
                 l28_fone = varchar(15) = Telefone 
                 l28_data = date = Data 
                 l28_hora = char(5) = Hora 
                 l28_ip = varchar(25) = Ip 
                 l28_liclicita = int4 = codigo da licitação 
                 ";
   //funcao construtor da classe 
   function cl_licbaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("licbaixa"); 
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
       $this->l28_sequencial = ($this->l28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_sequencial"]:$this->l28_sequencial);
       $this->l28_nome = ($this->l28_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_nome"]:$this->l28_nome);
       $this->l28_cnpj = ($this->l28_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_cnpj"]:$this->l28_cnpj);
       $this->l28_email = ($this->l28_email == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_email"]:$this->l28_email);
       $this->l28_endereco = ($this->l28_endereco == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_endereco"]:$this->l28_endereco);
       $this->l28_cidade = ($this->l28_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_cidade"]:$this->l28_cidade);
       $this->l28_fone = ($this->l28_fone == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_fone"]:$this->l28_fone);
       if($this->l28_data == ""){
         $this->l28_data_dia = ($this->l28_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_data_dia"]:$this->l28_data_dia);
         $this->l28_data_mes = ($this->l28_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_data_mes"]:$this->l28_data_mes);
         $this->l28_data_ano = ($this->l28_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_data_ano"]:$this->l28_data_ano);
         if($this->l28_data_dia != ""){
            $this->l28_data = $this->l28_data_ano."-".$this->l28_data_mes."-".$this->l28_data_dia;
         }
       }
       $this->l28_hora = ($this->l28_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_hora"]:$this->l28_hora);
       $this->l28_ip = ($this->l28_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_ip"]:$this->l28_ip);
       $this->l28_liclicita = ($this->l28_liclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_liclicita"]:$this->l28_liclicita);
     }else{
       $this->l28_sequencial = ($this->l28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l28_sequencial"]:$this->l28_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($l28_sequencial){ 
      $this->atualizacampos();
     if($this->l28_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "l28_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ/CPF nao Informado.";
       $this->erro_campo = "l28_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_email == null ){ 
       $this->erro_sql = " Campo Email nao Informado.";
       $this->erro_campo = "l28_email";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_endereco == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "l28_endereco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_cidade == null ){ 
       $this->erro_sql = " Campo Cidade nao Informado.";
       $this->erro_campo = "l28_cidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "l28_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "l28_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_ip == null ){ 
       $this->erro_sql = " Campo Ip nao Informado.";
       $this->erro_campo = "l28_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l28_liclicita == null ){ 
       $this->erro_sql = " Campo codigo da licitação nao Informado.";
       $this->erro_campo = "l28_liclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l28_sequencial == "" || $l28_sequencial == null ){
       $result = db_query("select nextval('licbaixa_l28_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: licbaixa_l28_sequencial_seq do campo: l28_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l28_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from licbaixa_l28_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l28_sequencial)){
         $this->erro_sql = " Campo l28_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l28_sequencial = $l28_sequencial; 
       }
     }
     if(($this->l28_sequencial == null) || ($this->l28_sequencial == "") ){ 
       $this->erro_sql = " Campo l28_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into licbaixa(
                                       l28_sequencial 
                                      ,l28_nome 
                                      ,l28_cnpj 
                                      ,l28_email 
                                      ,l28_endereco 
                                      ,l28_cidade 
                                      ,l28_fone 
                                      ,l28_data 
                                      ,l28_hora 
                                      ,l28_ip 
                                      ,l28_liclicita 
                       )
                values (
                                $this->l28_sequencial 
                               ,'$this->l28_nome' 
                               ,'$this->l28_cnpj' 
                               ,'$this->l28_email' 
                               ,'$this->l28_endereco' 
                               ,'$this->l28_cidade' 
                               ,'$this->l28_fone' 
                               ,".($this->l28_data == "null" || $this->l28_data == ""?"null":"'".$this->l28_data."'")." 
                               ,'$this->l28_hora' 
                               ,'$this->l28_ip' 
                               ,$this->l28_liclicita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro para baixa de edital ($this->l28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro para baixa de edital já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro para baixa de edital ($this->l28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l28_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l28_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9400,'$this->l28_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1616,9400,'','".AddSlashes(pg_result($resaco,0,'l28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9401,'','".AddSlashes(pg_result($resaco,0,'l28_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9402,'','".AddSlashes(pg_result($resaco,0,'l28_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9403,'','".AddSlashes(pg_result($resaco,0,'l28_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9404,'','".AddSlashes(pg_result($resaco,0,'l28_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9405,'','".AddSlashes(pg_result($resaco,0,'l28_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9406,'','".AddSlashes(pg_result($resaco,0,'l28_fone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9407,'','".AddSlashes(pg_result($resaco,0,'l28_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9408,'','".AddSlashes(pg_result($resaco,0,'l28_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9409,'','".AddSlashes(pg_result($resaco,0,'l28_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1616,9420,'','".AddSlashes(pg_result($resaco,0,'l28_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l28_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update licbaixa set ";
     $virgula = "";
     if(trim($this->l28_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_sequencial"])){ 
       $sql  .= $virgula." l28_sequencial = $this->l28_sequencial ";
       $virgula = ",";
       if(trim($this->l28_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "l28_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_nome"])){ 
       $sql  .= $virgula." l28_nome = '$this->l28_nome' ";
       $virgula = ",";
       if(trim($this->l28_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "l28_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_cnpj"])){ 
       $sql  .= $virgula." l28_cnpj = '$this->l28_cnpj' ";
       $virgula = ",";
       if(trim($this->l28_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ/CPF nao Informado.";
         $this->erro_campo = "l28_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_email"])){ 
       $sql  .= $virgula." l28_email = '$this->l28_email' ";
       $virgula = ",";
       if(trim($this->l28_email) == null ){ 
         $this->erro_sql = " Campo Email nao Informado.";
         $this->erro_campo = "l28_email";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_endereco"])){ 
       $sql  .= $virgula." l28_endereco = '$this->l28_endereco' ";
       $virgula = ",";
       if(trim($this->l28_endereco) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "l28_endereco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_cidade"])){ 
       $sql  .= $virgula." l28_cidade = '$this->l28_cidade' ";
       $virgula = ",";
       if(trim($this->l28_cidade) == null ){ 
         $this->erro_sql = " Campo Cidade nao Informado.";
         $this->erro_campo = "l28_cidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_fone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_fone"])){ 
       $sql  .= $virgula." l28_fone = '$this->l28_fone' ";
       $virgula = ",";
     }
     if(trim($this->l28_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l28_data_dia"] !="") ){ 
       $sql  .= $virgula." l28_data = '$this->l28_data' ";
       $virgula = ",";
       if(trim($this->l28_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "l28_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l28_data_dia"])){ 
         $sql  .= $virgula." l28_data = null ";
         $virgula = ",";
         if(trim($this->l28_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "l28_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l28_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_hora"])){ 
       $sql  .= $virgula." l28_hora = '$this->l28_hora' ";
       $virgula = ",";
       if(trim($this->l28_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "l28_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_ip"])){ 
       $sql  .= $virgula." l28_ip = '$this->l28_ip' ";
       $virgula = ",";
       if(trim($this->l28_ip) == null ){ 
         $this->erro_sql = " Campo Ip nao Informado.";
         $this->erro_campo = "l28_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l28_liclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l28_liclicita"])){ 
       $sql  .= $virgula." l28_liclicita = $this->l28_liclicita ";
       $virgula = ",";
       if(trim($this->l28_liclicita) == null ){ 
         $this->erro_sql = " Campo codigo da licitação nao Informado.";
         $this->erro_campo = "l28_liclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l28_sequencial!=null){
       $sql .= " l28_sequencial = $this->l28_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l28_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9400,'$this->l28_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1616,9400,'".AddSlashes(pg_result($resaco,$conresaco,'l28_sequencial'))."','$this->l28_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_nome"]))
           $resac = db_query("insert into db_acount values($acount,1616,9401,'".AddSlashes(pg_result($resaco,$conresaco,'l28_nome'))."','$this->l28_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_cnpj"]))
           $resac = db_query("insert into db_acount values($acount,1616,9402,'".AddSlashes(pg_result($resaco,$conresaco,'l28_cnpj'))."','$this->l28_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_email"]))
           $resac = db_query("insert into db_acount values($acount,1616,9403,'".AddSlashes(pg_result($resaco,$conresaco,'l28_email'))."','$this->l28_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_endereco"]))
           $resac = db_query("insert into db_acount values($acount,1616,9404,'".AddSlashes(pg_result($resaco,$conresaco,'l28_endereco'))."','$this->l28_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_cidade"]))
           $resac = db_query("insert into db_acount values($acount,1616,9405,'".AddSlashes(pg_result($resaco,$conresaco,'l28_cidade'))."','$this->l28_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_fone"]))
           $resac = db_query("insert into db_acount values($acount,1616,9406,'".AddSlashes(pg_result($resaco,$conresaco,'l28_fone'))."','$this->l28_fone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_data"]))
           $resac = db_query("insert into db_acount values($acount,1616,9407,'".AddSlashes(pg_result($resaco,$conresaco,'l28_data'))."','$this->l28_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_hora"]))
           $resac = db_query("insert into db_acount values($acount,1616,9408,'".AddSlashes(pg_result($resaco,$conresaco,'l28_hora'))."','$this->l28_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_ip"]))
           $resac = db_query("insert into db_acount values($acount,1616,9409,'".AddSlashes(pg_result($resaco,$conresaco,'l28_ip'))."','$this->l28_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l28_liclicita"]))
           $resac = db_query("insert into db_acount values($acount,1616,9420,'".AddSlashes(pg_result($resaco,$conresaco,'l28_liclicita'))."','$this->l28_liclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro para baixa de edital nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro para baixa de edital nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l28_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l28_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9400,'$l28_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1616,9400,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9401,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9402,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9403,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9404,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9405,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9406,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_fone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9407,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9408,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9409,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1616,9420,'','".AddSlashes(pg_result($resaco,$iresaco,'l28_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from licbaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l28_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l28_sequencial = $l28_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro para baixa de edital nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro para baixa de edital nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l28_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:licbaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from licbaixa ";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = licbaixa.l28_liclicita";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($l28_sequencial!=null ){
         $sql2 .= " where licbaixa.l28_sequencial = $l28_sequencial "; 
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
   function sql_query_file ( $l28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from licbaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($l28_sequencial!=null ){
         $sql2 .= " where licbaixa.l28_sequencial = $l28_sequencial "; 
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