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

//MODULO: Compras
//CLASSE DA ENTIDADE pcfornecon
class cl_pcfornecon { 
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
   var $pc63_contabanco = 0; 
   var $pc63_numcgm = 0; 
   var $pc63_banco = null; 
   var $pc63_agencia = null; 
   var $pc63_conta = null; 
   var $pc63_id_usuario = 0; 
   var $pc63_cnpjcpf = null; 
   var $pc63_agencia_dig = null; 
   var $pc63_conta_dig = null; 
   var $pc63_dataconf_dia = null; 
   var $pc63_dataconf_mes = null; 
   var $pc63_dataconf_ano = null; 
   var $pc63_dataconf = null; 
   var $pc63_identcli = null; 
   var $pc63_codigooperacao = null; 
   var $pc63_tipoconta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc63_contabanco = int4 = Código Conta 
                 pc63_numcgm = int4 = Fornecedor 
                 pc63_banco = varchar(10) = Banco 
                 pc63_agencia = varchar(10) = Agência 
                 pc63_conta = varchar(40) = Conta 
                 pc63_id_usuario = int4 = Usuário 
                 pc63_cnpjcpf = varchar(15) = CNPJ/CPF 
                 pc63_agencia_dig = varchar(2) = Dígito verificador da agência 
                 pc63_conta_dig = varchar(2) = Dígito verificador da conta 
                 pc63_dataconf = date = Conferido 
                 pc63_identcli = varchar(20) = Identificação do cliente 
                 pc63_codigooperacao = varchar(4) = Código da Operação 
                 pc63_tipoconta = int4 = Tipo da Conta 
                 ";
   //funcao construtor da classe 
   function cl_pcfornecon() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornecon"); 
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
       $this->pc63_contabanco = ($this->pc63_contabanco == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_contabanco"]:$this->pc63_contabanco);
       $this->pc63_numcgm = ($this->pc63_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_numcgm"]:$this->pc63_numcgm);
       $this->pc63_banco = ($this->pc63_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_banco"]:$this->pc63_banco);
       $this->pc63_agencia = ($this->pc63_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_agencia"]:$this->pc63_agencia);
       $this->pc63_conta = ($this->pc63_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_conta"]:$this->pc63_conta);
       $this->pc63_id_usuario = ($this->pc63_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_id_usuario"]:$this->pc63_id_usuario);
       $this->pc63_cnpjcpf = ($this->pc63_cnpjcpf == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_cnpjcpf"]:$this->pc63_cnpjcpf);
       $this->pc63_agencia_dig = ($this->pc63_agencia_dig == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_agencia_dig"]:$this->pc63_agencia_dig);
       $this->pc63_conta_dig = ($this->pc63_conta_dig == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_conta_dig"]:$this->pc63_conta_dig);
       if($this->pc63_dataconf == ""){
         $this->pc63_dataconf_dia = ($this->pc63_dataconf_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_dataconf_dia"]:$this->pc63_dataconf_dia);
         $this->pc63_dataconf_mes = ($this->pc63_dataconf_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_dataconf_mes"]:$this->pc63_dataconf_mes);
         $this->pc63_dataconf_ano = ($this->pc63_dataconf_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_dataconf_ano"]:$this->pc63_dataconf_ano);
         if($this->pc63_dataconf_dia != ""){
            $this->pc63_dataconf = $this->pc63_dataconf_ano."-".$this->pc63_dataconf_mes."-".$this->pc63_dataconf_dia;
         }
       }
       $this->pc63_identcli = ($this->pc63_identcli == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_identcli"]:$this->pc63_identcli);
       $this->pc63_codigooperacao = ($this->pc63_codigooperacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_codigooperacao"]:$this->pc63_codigooperacao);
       $this->pc63_tipoconta = ($this->pc63_tipoconta == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_tipoconta"]:$this->pc63_tipoconta);
     }else{
       $this->pc63_contabanco = ($this->pc63_contabanco == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_contabanco"]:$this->pc63_contabanco);
       $this->pc63_conta = ($this->pc63_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["pc63_conta"]:$this->pc63_conta);
     }
   }
   // funcao para inclusao
   function incluir ($pc63_contabanco){ 
      $this->atualizacampos();
     if($this->pc63_numcgm == null ){ 
       $this->erro_sql = " Campo Fornecedor nao Informado.";
       $this->erro_campo = "pc63_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc63_banco == null ){ 
       $this->erro_sql = " Campo Banco nao Informado.";
       $this->erro_campo = "pc63_banco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc63_agencia == null ){ 
       $this->erro_sql = " Campo Agência nao Informado.";
       $this->erro_campo = "pc63_agencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc63_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "pc63_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc63_cnpjcpf == null ){ 
       $this->pc63_cnpjcpf = "0";
     }
     if($this->pc63_agencia_dig == null ){ 
       $this->erro_sql = " Campo Dígito verificador da agência nao Informado.";
       $this->erro_campo = "pc63_agencia_dig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc63_conta_dig == null ){ 
       $this->erro_sql = " Campo Dígito verificador da conta nao Informado.";
       $this->erro_campo = "pc63_conta_dig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc63_dataconf == null ){ 
       $this->pc63_dataconf = "null";
     }
     if($this->pc63_tipoconta == null ){ 
       $this->pc63_tipoconta = "1";
     }
     if($pc63_contabanco == "" || $pc63_contabanco == null ){
       $result = db_query("select nextval('pcfornecon_pc63_contabanco_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcfornecon_pc63_contabanco_seq do campo: pc63_contabanco"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc63_contabanco = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcfornecon_pc63_contabanco_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc63_contabanco)){
         $this->erro_sql = " Campo pc63_contabanco maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc63_contabanco = $pc63_contabanco; 
       }
     }
     if(($this->pc63_contabanco == null) || ($this->pc63_contabanco == "") ){ 
       $this->erro_sql = " Campo pc63_contabanco nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornecon(
                                       pc63_contabanco 
                                      ,pc63_numcgm 
                                      ,pc63_banco 
                                      ,pc63_agencia 
                                      ,pc63_conta 
                                      ,pc63_id_usuario 
                                      ,pc63_cnpjcpf 
                                      ,pc63_agencia_dig 
                                      ,pc63_conta_dig 
                                      ,pc63_dataconf 
                                      ,pc63_identcli 
                                      ,pc63_codigooperacao 
                                      ,pc63_tipoconta 
                       )
                values (
                                $this->pc63_contabanco 
                               ,$this->pc63_numcgm 
                               ,'$this->pc63_banco' 
                               ,'$this->pc63_agencia' 
                               ,'$this->pc63_conta' 
                               ,$this->pc63_id_usuario 
                               ,'$this->pc63_cnpjcpf' 
                               ,'$this->pc63_agencia_dig' 
                               ,'$this->pc63_conta_dig' 
                               ,".($this->pc63_dataconf == "null" || $this->pc63_dataconf == ""?"null":"'".$this->pc63_dataconf."'")." 
                               ,'$this->pc63_identcli' 
                               ,'$this->pc63_codigooperacao' 
                               ,$this->pc63_tipoconta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contas banco dos fornecedores ($this->pc63_contabanco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contas banco dos fornecedores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contas banco dos fornecedores ($this->pc63_contabanco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc63_contabanco;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc63_contabanco));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6003,'$this->pc63_contabanco','I')");
       $resac = db_query("insert into db_acount values($acount,963,6003,'','".AddSlashes(pg_result($resaco,0,'pc63_contabanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,6004,'','".AddSlashes(pg_result($resaco,0,'pc63_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,6005,'','".AddSlashes(pg_result($resaco,0,'pc63_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,6006,'','".AddSlashes(pg_result($resaco,0,'pc63_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,6007,'','".AddSlashes(pg_result($resaco,0,'pc63_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,6008,'','".AddSlashes(pg_result($resaco,0,'pc63_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,6580,'','".AddSlashes(pg_result($resaco,0,'pc63_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,6785,'','".AddSlashes(pg_result($resaco,0,'pc63_agencia_dig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,7181,'','".AddSlashes(pg_result($resaco,0,'pc63_conta_dig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,7262,'','".AddSlashes(pg_result($resaco,0,'pc63_dataconf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,9616,'','".AddSlashes(pg_result($resaco,0,'pc63_identcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,15305,'','".AddSlashes(pg_result($resaco,0,'pc63_codigooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,963,15306,'','".AddSlashes(pg_result($resaco,0,'pc63_tipoconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc63_contabanco=null) { 
      $this->atualizacampos();
     $sql = " update pcfornecon set ";
     $virgula = "";
     if(trim($this->pc63_contabanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_contabanco"])){ 
       $sql  .= $virgula." pc63_contabanco = $this->pc63_contabanco ";
       $virgula = ",";
       if(trim($this->pc63_contabanco) == null ){ 
         $this->erro_sql = " Campo Código Conta nao Informado.";
         $this->erro_campo = "pc63_contabanco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_numcgm"])){ 
       $sql  .= $virgula." pc63_numcgm = $this->pc63_numcgm ";
       $virgula = ",";
       if(trim($this->pc63_numcgm) == null ){ 
         $this->erro_sql = " Campo Fornecedor nao Informado.";
         $this->erro_campo = "pc63_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_banco"])){ 
       $sql  .= $virgula." pc63_banco = '$this->pc63_banco' ";
       $virgula = ",";
       if(trim($this->pc63_banco) == null ){ 
         $this->erro_sql = " Campo Banco nao Informado.";
         $this->erro_campo = "pc63_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_agencia"])){ 
       $sql  .= $virgula." pc63_agencia = '$this->pc63_agencia' ";
       $virgula = ",";
       if(trim($this->pc63_agencia) == null ){ 
         $this->erro_sql = " Campo Agência nao Informado.";
         $this->erro_campo = "pc63_agencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_conta"])){ 
       $sql  .= $virgula." pc63_conta = '$this->pc63_conta' ";
       $virgula = ",";
       if(trim($this->pc63_conta) == null ){ 
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "pc63_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_id_usuario"])){ 
       $sql  .= $virgula." pc63_id_usuario = $this->pc63_id_usuario ";
       $virgula = ",";
       if(trim($this->pc63_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "pc63_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_cnpjcpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_cnpjcpf"])){ 
       $sql  .= $virgula." pc63_cnpjcpf = '$this->pc63_cnpjcpf' ";
       $virgula = ",";
     }
     if(trim($this->pc63_agencia_dig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_agencia_dig"])){ 
       $sql  .= $virgula." pc63_agencia_dig = '$this->pc63_agencia_dig' ";
       $virgula = ",";
       if(trim($this->pc63_agencia_dig) == null ){ 
         $this->erro_sql = " Campo Dígito verificador da agência nao Informado.";
         $this->erro_campo = "pc63_agencia_dig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_conta_dig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_conta_dig"])){ 
       $sql  .= $virgula." pc63_conta_dig = '$this->pc63_conta_dig' ";
       $virgula = ",";
       if(trim($this->pc63_conta_dig) == null ){ 
         $this->erro_sql = " Campo Dígito verificador da conta nao Informado.";
         $this->erro_campo = "pc63_conta_dig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc63_dataconf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_dataconf_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc63_dataconf_dia"] !="") ){ 
       $sql  .= $virgula." pc63_dataconf = '$this->pc63_dataconf' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_dataconf_dia"])){ 
         $sql  .= $virgula." pc63_dataconf = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc63_identcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_identcli"])){ 
       $sql  .= $virgula." pc63_identcli = '$this->pc63_identcli' ";
       $virgula = ",";
     }
     if(trim($this->pc63_codigooperacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_codigooperacao"])){ 
       $sql  .= $virgula." pc63_codigooperacao = '$this->pc63_codigooperacao' ";
       $virgula = ",";
     }
     if(trim($this->pc63_tipoconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc63_tipoconta"])){ 
        if(trim($this->pc63_tipoconta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc63_tipoconta"])){ 
           $this->pc63_tipoconta = "0" ; 
        } 
       $sql  .= $virgula." pc63_tipoconta = $this->pc63_tipoconta ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc63_contabanco!=null){
       $sql .= " pc63_contabanco = $this->pc63_contabanco";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc63_contabanco));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6003,'$this->pc63_contabanco','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_contabanco"]) || $this->pc63_contabanco != "")
           $resac = db_query("insert into db_acount values($acount,963,6003,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_contabanco'))."','$this->pc63_contabanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_numcgm"]) || $this->pc63_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,963,6004,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_numcgm'))."','$this->pc63_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_banco"]) || $this->pc63_banco != "")
           $resac = db_query("insert into db_acount values($acount,963,6005,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_banco'))."','$this->pc63_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_agencia"]) || $this->pc63_agencia != "")
           $resac = db_query("insert into db_acount values($acount,963,6006,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_agencia'))."','$this->pc63_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_conta"]) || $this->pc63_conta != "")
           $resac = db_query("insert into db_acount values($acount,963,6007,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_conta'))."','$this->pc63_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_id_usuario"]) || $this->pc63_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,963,6008,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_id_usuario'))."','$this->pc63_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_cnpjcpf"]) || $this->pc63_cnpjcpf != "")
           $resac = db_query("insert into db_acount values($acount,963,6580,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_cnpjcpf'))."','$this->pc63_cnpjcpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_agencia_dig"]) || $this->pc63_agencia_dig != "")
           $resac = db_query("insert into db_acount values($acount,963,6785,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_agencia_dig'))."','$this->pc63_agencia_dig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_conta_dig"]) || $this->pc63_conta_dig != "")
           $resac = db_query("insert into db_acount values($acount,963,7181,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_conta_dig'))."','$this->pc63_conta_dig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_dataconf"]) || $this->pc63_dataconf != "")
           $resac = db_query("insert into db_acount values($acount,963,7262,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_dataconf'))."','$this->pc63_dataconf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_identcli"]) || $this->pc63_identcli != "")
           $resac = db_query("insert into db_acount values($acount,963,9616,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_identcli'))."','$this->pc63_identcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_codigooperacao"]) || $this->pc63_codigooperacao != "")
           $resac = db_query("insert into db_acount values($acount,963,15305,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_codigooperacao'))."','$this->pc63_codigooperacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc63_tipoconta"]) || $this->pc63_tipoconta != "")
           $resac = db_query("insert into db_acount values($acount,963,15306,'".AddSlashes(pg_result($resaco,$conresaco,'pc63_tipoconta'))."','$this->pc63_tipoconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas banco dos fornecedores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc63_contabanco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas banco dos fornecedores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc63_contabanco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc63_contabanco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc63_contabanco=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc63_contabanco));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6003,'$pc63_contabanco','E')");
         $resac = db_query("insert into db_acount values($acount,963,6003,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_contabanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,6004,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,6005,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,6006,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,6007,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,6008,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,6580,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,6785,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_agencia_dig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,7181,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_conta_dig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,7262,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_dataconf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,9616,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_identcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,15305,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_codigooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,963,15306,'','".AddSlashes(pg_result($resaco,$iresaco,'pc63_tipoconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornecon
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc63_contabanco != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc63_contabanco = $pc63_contabanco ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas banco dos fornecedores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc63_contabanco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas banco dos fornecedores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc63_contabanco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc63_contabanco;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcfornecon";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc63_contabanco=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecon ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcfornecon.pc63_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcfornecon.pc63_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc63_contabanco!=null ){
         $sql2 .= " where pcfornecon.pc63_contabanco = $pc63_contabanco "; 
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
   // funcao do sql 
   function sql_query_file ( $pc63_contabanco=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecon ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc63_contabanco!=null ){
         $sql2 .= " where pcfornecon.pc63_contabanco = $pc63_contabanco "; 
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
   function sql_query_conta ( $pc63_contabanco=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcfornecon ";
     $sql .= "      inner join pcforne  on  pcforne.pc60_numcgm = pcfornecon.pc63_numcgm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcforne.pc60_numcgm";
     $sql .= "      inner join conplanoconta  on pc63_banco = c63_banco and c63_anousu".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
 if($pc63_contabanco!=null ){
         $sql2 .= " where pcfornecon.pc63_contabanco = $pc63_contabanco ";
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
   function sql_query_empenho ( $pc63_contabanco=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcfornecon ";
     $sql .= "      inner join pcforneconpad  on  pc63_contabanco = pc64_contabanco";
     $sql .= "      inner join empempenho  on  e60_numcgm = pc63_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($pc63_contabanco!=null ){
         $sql2 .= " where pcfornecon.pc63_contabanco = $pc63_contabanco ";
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
   function sql_query_padrao ( $pc63_contabanco=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcfornecon ";
     $sql .= "      inner join pcforneconpad  on  pc63_contabanco = pc64_contabanco";
     $sql2 = "";
     if($dbwhere==""){
       if($pc63_contabanco!=null ){
         $sql2 .= " where pcfornecon.pc63_contabanco = $pc63_contabanco ";
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
   function sql_query_lefpadrao ( $pc63_contabanco=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcfornecon ";
     $sql .= "      left join pcforneconpad  on  pc63_contabanco = pc64_contabanco";
     $sql2 = "";
     if($dbwhere==""){
       if($pc63_contabanco!=null ){
         $sql2 .= " where pcfornecon.pc63_contabanco = $pc63_contabanco ";
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