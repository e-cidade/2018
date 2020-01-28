<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhsuspensaopag
class cl_rhsuspensaopag { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $rh101_sequencial = 0; 
   var $rh101_regist = 0; 
   var $rh101_dtcadastro_dia = null; 
   var $rh101_dtcadastro_mes = null; 
   var $rh101_dtcadastro_ano = null; 
   var $rh101_dtcadastro = null; 
   var $rh101_usuario = 0; 
   var $rh101_dtinicial_dia = null; 
   var $rh101_dtinicial_mes = null; 
   var $rh101_dtinicial_ano = null; 
   var $rh101_dtinicial = null; 
   var $rh101_dtfinal_dia = null; 
   var $rh101_dtfinal_mes = null; 
   var $rh101_dtfinal_ano = null; 
   var $rh101_dtfinal = null; 
   var $rh101_dtdesativacao_dia = null; 
   var $rh101_dtdesativacao_mes = null; 
   var $rh101_dtdesativacao_ano = null; 
   var $rh101_dtdesativacao = null; 
   var $rh101_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh101_sequencial = int4 = Sequencial 
                 rh101_regist = int4 = Matrícula 
                 rh101_dtcadastro = date = Data de Cadastro 
                 rh101_usuario = int4 = Usuário 
                 rh101_dtinicial = date = Data de Início 
                 rh101_dtfinal = date = Data de Fim 
                 rh101_dtdesativacao = date = Data da Desativação 
                 rh101_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_rhsuspensaopag() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhsuspensaopag"); 
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
       $this->rh101_sequencial = ($this->rh101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh101_sequencial"]:$this->rh101_sequencial);
       $this->rh101_regist = ($this->rh101_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh101_regist"]:$this->rh101_regist);
       if($this->rh101_dtcadastro == ""){
         $this->rh101_dtcadastro_dia = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtcadastro_dia"];
         $this->rh101_dtcadastro_mes = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtcadastro_mes"];
         $this->rh101_dtcadastro_ano = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtcadastro_ano"];
         if($this->rh101_dtcadastro_dia != ""){
            $this->rh101_dtcadastro = $this->rh101_dtcadastro_ano."-".$this->rh101_dtcadastro_mes."-".$this->rh101_dtcadastro_dia;
         }
       }
       $this->rh101_usuario = ($this->rh101_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh101_usuario"]:$this->rh101_usuario);
       if($this->rh101_dtinicial == ""){
         $this->rh101_dtinicial_dia = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtinicial_dia"];
         $this->rh101_dtinicial_mes = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtinicial_mes"];
         $this->rh101_dtinicial_ano = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtinicial_ano"];
         if($this->rh101_dtinicial_dia != ""){
            $this->rh101_dtinicial = $this->rh101_dtinicial_ano."-".$this->rh101_dtinicial_mes."-".$this->rh101_dtinicial_dia;
         }
       }
       if($this->rh101_dtfinal == ""){
         $this->rh101_dtfinal_dia = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtfinal_dia"];
         $this->rh101_dtfinal_mes = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtfinal_mes"];
         $this->rh101_dtfinal_ano = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtfinal_ano"];
         if($this->rh101_dtfinal_dia != ""){
            $this->rh101_dtfinal = $this->rh101_dtfinal_ano."-".$this->rh101_dtfinal_mes."-".$this->rh101_dtfinal_dia;
         }
       }
       if($this->rh101_dtdesativacao == ""){
         $this->rh101_dtdesativacao_dia = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtdesativacao_dia"];
         $this->rh101_dtdesativacao_mes = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtdesativacao_mes"];
         $this->rh101_dtdesativacao_ano = @$GLOBALS["HTTP_POST_VARS"]["rh101_dtdesativacao_ano"];
         if($this->rh101_dtdesativacao_dia != ""){
            $this->rh101_dtdesativacao = $this->rh101_dtdesativacao_ano."-".$this->rh101_dtdesativacao_mes."-".$this->rh101_dtdesativacao_dia;
         }
       }
       $this->rh101_obs = ($this->rh101_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["rh101_obs"]:$this->rh101_obs);
     }else{
       $this->rh101_sequencial = ($this->rh101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh101_sequencial"]:$this->rh101_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh101_sequencial){ 
      $this->atualizacampos();
     if($this->rh101_regist == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "rh101_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh101_dtcadastro == null ){ 
       $this->erro_sql = " Campo Data de Cadastro nao Informado.";
       $this->erro_campo = "rh101_dtcadastro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh101_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "rh101_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh101_dtinicial == null ){ 
       $this->erro_sql = " Campo Data de Início nao Informado.";
       $this->erro_campo = "rh101_dtinicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh101_dtfinal == null ){ 
       $this->erro_sql = " Campo Data de Fim nao Informado.";
       $this->erro_campo = "rh101_dtfinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh101_dtdesativacao == null ){ 
       $this->rh101_dtdesativacao = "null";
     }
     if($rh101_sequencial == "" || $rh101_sequencial == null ){
       $result = @pg_query("select nextval('rhsuspensaopag_rh101_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhsuspensaopag_rh101_sequencial_seq do campo: rh101_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh101_sequencial = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from rhsuspensaopag_rh101_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh101_sequencial)){
         $this->erro_sql = " Campo rh101_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh101_sequencial = $rh101_sequencial; 
       }
     }
     if(($this->rh101_sequencial == null) || ($this->rh101_sequencial == "") ){ 
       $this->erro_sql = " Campo rh101_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into rhsuspensaopag(
                                       rh101_sequencial 
                                      ,rh101_regist 
                                      ,rh101_dtcadastro 
                                      ,rh101_usuario 
                                      ,rh101_dtinicial 
                                      ,rh101_dtfinal 
                                      ,rh101_dtdesativacao 
                                      ,rh101_obs 
                       )
                values (
                                $this->rh101_sequencial 
                               ,$this->rh101_regist 
                               ,".($this->rh101_dtcadastro == "null" || $this->rh101_dtcadastro == ""?"null":"'".$this->rh101_dtcadastro."'")." 
                               ,$this->rh101_usuario 
                               ,".($this->rh101_dtinicial == "null" || $this->rh101_dtinicial == ""?"null":"'".$this->rh101_dtinicial."'")." 
                               ,".($this->rh101_dtfinal == "null" || $this->rh101_dtfinal == ""?"null":"'".$this->rh101_dtfinal."'")." 
                               ,".($this->rh101_dtdesativacao == "null" || $this->rh101_dtdesativacao == ""?"null":"'".$this->rh101_dtdesativacao."'")." 
                               ,'$this->rh101_obs' 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Suspensão de Pagamento ($this->rh101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Suspensão de Pagamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Suspensão de Pagamento ($this->rh101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh101_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->rh101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18098,'$this->rh101_sequencial','I')");
       $resac = pg_query("insert into db_acount values($acount,3203,18098,'','".pg_result($resaco,0,'rh101_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18099,'','".pg_result($resaco,0,'rh101_regist')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18100,'','".pg_result($resaco,0,'rh101_dtcadastro')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18101,'','".pg_result($resaco,0,'rh101_usuario')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18102,'','".pg_result($resaco,0,'rh101_dtinicial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18103,'','".pg_result($resaco,0,'rh101_dtfinal')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18104,'','".pg_result($resaco,0,'rh101_dtdesativacao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18105,'','".pg_result($resaco,0,'rh101_obs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh101_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhsuspensaopag set ";
     $virgula = "";
     if(trim($this->rh101_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_sequencial"])){ 
        if(trim($this->rh101_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh101_sequencial"])){ 
           $this->rh101_sequencial = "0" ; 
        } 
       $sql  .= $virgula." rh101_sequencial = $this->rh101_sequencial ";
       $virgula = ",";
       if(trim($this->rh101_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh101_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh101_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_regist"])){ 
        if(trim($this->rh101_regist)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh101_regist"])){ 
           $this->rh101_regist = "0" ; 
        } 
       $sql  .= $virgula." rh101_regist = $this->rh101_regist ";
       $virgula = ",";
       if(trim($this->rh101_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "rh101_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh101_dtcadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtcadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh101_dtcadastro_dia"] !="") ){ 
       $sql  .= $virgula." rh101_dtcadastro = '$this->rh101_dtcadastro' ";
       $virgula = ",";
       if(trim($this->rh101_dtcadastro) == null ){ 
         $this->erro_sql = " Campo Data de Cadastro nao Informado.";
         $this->erro_campo = "rh101_dtcadastro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtcadastro_dia"])){ 
         $sql  .= $virgula." rh101_dtcadastro = null ";
         $virgula = ",";
         if(trim($this->rh101_dtcadastro) == null ){ 
           $this->erro_sql = " Campo Data de Cadastro nao Informado.";
           $this->erro_campo = "rh101_dtcadastro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh101_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_usuario"])){ 
        if(trim($this->rh101_usuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh101_usuario"])){ 
           $this->rh101_usuario = "0" ; 
        } 
       $sql  .= $virgula." rh101_usuario = $this->rh101_usuario ";
       $virgula = ",";
       if(trim($this->rh101_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "rh101_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh101_dtinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh101_dtinicial_dia"] !="") ){ 
       $sql  .= $virgula." rh101_dtinicial = '$this->rh101_dtinicial' ";
       $virgula = ",";
       if(trim($this->rh101_dtinicial) == null ){ 
         $this->erro_sql = " Campo Data de Início nao Informado.";
         $this->erro_campo = "rh101_dtinicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtinicial_dia"])){ 
         $sql  .= $virgula." rh101_dtinicial = null ";
         $virgula = ",";
         if(trim($this->rh101_dtinicial) == null ){ 
           $this->erro_sql = " Campo Data de Início nao Informado.";
           $this->erro_campo = "rh101_dtinicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh101_dtfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtfinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh101_dtfinal_dia"] !="") ){ 
       $sql  .= $virgula." rh101_dtfinal = '$this->rh101_dtfinal' ";
       $virgula = ",";
       if(trim($this->rh101_dtfinal) == null ){ 
         $this->erro_sql = " Campo Data de Fim nao Informado.";
         $this->erro_campo = "rh101_dtfinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtfinal_dia"])){ 
         $sql  .= $virgula." rh101_dtfinal = null ";
         $virgula = ",";
         if(trim($this->rh101_dtfinal) == null ){ 
           $this->erro_sql = " Campo Data de Fim nao Informado.";
           $this->erro_campo = "rh101_dtfinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh101_dtdesativacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtdesativacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh101_dtdesativacao_dia"] !="") ){ 
       $sql  .= $virgula." rh101_dtdesativacao = '$this->rh101_dtdesativacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtdesativacao_dia"])){ 
         $sql  .= $virgula." rh101_dtdesativacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh101_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh101_obs"])){ 
       $sql  .= $virgula." rh101_obs = '$this->rh101_obs' ";
       $virgula = ",";
     }
     $sql .= " where  rh101_sequencial = $this->rh101_sequencial
";
     $resaco = $this->sql_record($this->sql_query_file($this->rh101_sequencial));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18098,'$this->rh101_sequencial','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_sequencial"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18098,'".pg_result($resaco,0,'rh101_sequencial')."','$this->rh101_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_regist"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18099,'".pg_result($resaco,0,'rh101_regist')."','$this->rh101_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtcadastro"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18100,'".pg_result($resaco,0,'rh101_dtcadastro')."','$this->rh101_dtcadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_usuario"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18101,'".pg_result($resaco,0,'rh101_usuario')."','$this->rh101_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtinicial"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18102,'".pg_result($resaco,0,'rh101_dtinicial')."','$this->rh101_dtinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtfinal"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18103,'".pg_result($resaco,0,'rh101_dtfinal')."','$this->rh101_dtfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_dtdesativacao"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18104,'".pg_result($resaco,0,'rh101_dtdesativacao')."','$this->rh101_dtdesativacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh101_obs"]))
         $resac = pg_query("insert into db_acount values($acount,3203,18105,'".pg_result($resaco,0,'rh101_obs')."','$this->rh101_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspensão de Pagamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspensão de Pagamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh101_sequencial=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->rh101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18098,'$this->rh101_sequencial','E')");
       $resac = pg_query("insert into db_acount values($acount,3203,18098,'','".pg_result($resaco,0,'rh101_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18099,'','".pg_result($resaco,0,'rh101_regist')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18100,'','".pg_result($resaco,0,'rh101_dtcadastro')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18101,'','".pg_result($resaco,0,'rh101_usuario')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18102,'','".pg_result($resaco,0,'rh101_dtinicial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18103,'','".pg_result($resaco,0,'rh101_dtfinal')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18104,'','".pg_result($resaco,0,'rh101_dtdesativacao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3203,18105,'','".pg_result($resaco,0,'rh101_obs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from rhsuspensaopag
                    where ";
     $sql2 = "";
      if($this->rh101_sequencial != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " rh101_sequencial = $this->rh101_sequencial ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspensão de Pagamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspensão de Pagamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsuspensaopag ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhsuspensaopag.rh101_usuario";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhsuspensaopag.rh101_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($rh101_sequencial!=null ){
         $sql2 .= " where rhsuspensaopag.rh101_sequencial = $rh101_sequencial "; 
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
   function sql_query_file ( $rh101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsuspensaopag ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh101_sequencial!=null ){
         $sql2 .= " where rhsuspensaopag.rh101_sequencial = $rh101_sequencial "; 
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