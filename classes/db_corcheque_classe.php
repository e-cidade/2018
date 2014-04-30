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

//MODULO: caixa
//CLASSE DA ENTIDADE corcheque
class cl_corcheque { 
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
   var $k12_codcorcheque = 0; 
   var $k12_banco = 0; 
   var $k12_agencia = null; 
   var $k12_nominal = 'f'; 
   var $k12_numero = null; 
   var $k12_dtcheque_dia = null; 
   var $k12_dtcheque_mes = null; 
   var $k12_dtcheque_ano = null; 
   var $k12_dtcheque = null; 
   var $k12_vlrcheque = 0; 
   var $k12_usuario = 0; 
   var $k12_dtinc_dia = null; 
   var $k12_dtinc_mes = null; 
   var $k12_dtinc_ano = null; 
   var $k12_dtinc = null; 
   var $k12_horainc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k12_codcorcheque = int4 = Código 
                 k12_banco = int4 = Código do Banco 
                 k12_agencia = char(5) = Agência 
                 k12_nominal = bool = Nominal 
                 k12_numero = varchar(20) = Número do Cheque 
                 k12_dtcheque = date = Data 
                 k12_vlrcheque = float4 = Valor 
                 k12_usuario = int4 = Cod. Usuário 
                 k12_dtinc = date = Data da inclusão 
                 k12_horainc = char(5) = Hora da Inclusão 
                 ";
   //funcao construtor da classe 
   function cl_corcheque() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("corcheque"); 
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
       $this->k12_codcorcheque = ($this->k12_codcorcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_codcorcheque"]:$this->k12_codcorcheque);
       $this->k12_banco = ($this->k12_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_banco"]:$this->k12_banco);
       $this->k12_agencia = ($this->k12_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_agencia"]:$this->k12_agencia);
       $this->k12_nominal = ($this->k12_nominal == "f"?@$GLOBALS["HTTP_POST_VARS"]["k12_nominal"]:$this->k12_nominal);
       $this->k12_numero = ($this->k12_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numero"]:$this->k12_numero);
       if($this->k12_dtcheque == ""){
         $this->k12_dtcheque_dia = ($this->k12_dtcheque_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtcheque_dia"]:$this->k12_dtcheque_dia);
         $this->k12_dtcheque_mes = ($this->k12_dtcheque_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtcheque_mes"]:$this->k12_dtcheque_mes);
         $this->k12_dtcheque_ano = ($this->k12_dtcheque_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtcheque_ano"]:$this->k12_dtcheque_ano);
         if($this->k12_dtcheque_dia != ""){
            $this->k12_dtcheque = $this->k12_dtcheque_ano."-".$this->k12_dtcheque_mes."-".$this->k12_dtcheque_dia;
         }
       }
       $this->k12_vlrcheque = ($this->k12_vlrcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_vlrcheque"]:$this->k12_vlrcheque);
       $this->k12_usuario = ($this->k12_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_usuario"]:$this->k12_usuario);
       if($this->k12_dtinc == ""){
         $this->k12_dtinc_dia = ($this->k12_dtinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtinc_dia"]:$this->k12_dtinc_dia);
         $this->k12_dtinc_mes = ($this->k12_dtinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtinc_mes"]:$this->k12_dtinc_mes);
         $this->k12_dtinc_ano = ($this->k12_dtinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtinc_ano"]:$this->k12_dtinc_ano);
         if($this->k12_dtinc_dia != ""){
            $this->k12_dtinc = $this->k12_dtinc_ano."-".$this->k12_dtinc_mes."-".$this->k12_dtinc_dia;
         }
       }
       $this->k12_horainc = ($this->k12_horainc == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_horainc"]:$this->k12_horainc);
     }else{
       $this->k12_codcorcheque = ($this->k12_codcorcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_codcorcheque"]:$this->k12_codcorcheque);
     }
   }
   // funcao para inclusao
   function incluir ($k12_codcorcheque){ 
      $this->atualizacampos();
     if($this->k12_banco == null ){ 
       $this->erro_sql = " Campo Código do Banco nao Informado.";
       $this->erro_campo = "k12_banco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_agencia == null ){ 
       $this->erro_sql = " Campo Agência nao Informado.";
       $this->erro_campo = "k12_agencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_nominal == null ){ 
       $this->erro_sql = " Campo Nominal nao Informado.";
       $this->erro_campo = "k12_nominal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_numero == null ){ 
       $this->erro_sql = " Campo Número do Cheque nao Informado.";
       $this->erro_campo = "k12_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_dtcheque == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k12_dtcheque_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_vlrcheque == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k12_vlrcheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "k12_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_dtinc == null ){ 
       $this->erro_sql = " Campo Data da inclusão nao Informado.";
       $this->erro_campo = "k12_dtinc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_horainc == null ){ 
       $this->erro_sql = " Campo Hora da Inclusão nao Informado.";
       $this->erro_campo = "k12_horainc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k12_codcorcheque == "" || $k12_codcorcheque == null ){
       $result = db_query("select nextval('corcheque_k12_codcorcheque_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: corcheque_k12_codcorcheque_seq do campo: k12_codcorcheque"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k12_codcorcheque = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from corcheque_k12_codcorcheque_seq");
       if(($result != false) && (pg_result($result,0,0) < $k12_codcorcheque)){
         $this->erro_sql = " Campo k12_codcorcheque maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k12_codcorcheque = $k12_codcorcheque; 
       }
     }
     if(($this->k12_codcorcheque == null) || ($this->k12_codcorcheque == "") ){ 
       $this->erro_sql = " Campo k12_codcorcheque nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into corcheque(
                                       k12_codcorcheque 
                                      ,k12_banco 
                                      ,k12_agencia 
                                      ,k12_nominal 
                                      ,k12_numero 
                                      ,k12_dtcheque 
                                      ,k12_vlrcheque 
                                      ,k12_usuario 
                                      ,k12_dtinc 
                                      ,k12_horainc 
                       )
                values (
                                $this->k12_codcorcheque 
                               ,$this->k12_banco 
                               ,'$this->k12_agencia' 
                               ,'$this->k12_nominal' 
                               ,'$this->k12_numero' 
                               ,".($this->k12_dtcheque == "null" || $this->k12_dtcheque == ""?"null":"'".$this->k12_dtcheque."'")." 
                               ,$this->k12_vlrcheque 
                               ,$this->k12_usuario 
                               ,".($this->k12_dtinc == "null" || $this->k12_dtinc == ""?"null":"'".$this->k12_dtinc."'")." 
                               ,'$this->k12_horainc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "corcheque ($this->k12_codcorcheque) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "corcheque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "corcheque ($this->k12_codcorcheque) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_codcorcheque;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k12_codcorcheque));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9835,'$this->k12_codcorcheque','I')");
       $resac = db_query("insert into db_acount values($acount,1689,9835,'','".AddSlashes(pg_result($resaco,0,'k12_codcorcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9836,'','".AddSlashes(pg_result($resaco,0,'k12_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9837,'','".AddSlashes(pg_result($resaco,0,'k12_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9838,'','".AddSlashes(pg_result($resaco,0,'k12_nominal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9839,'','".AddSlashes(pg_result($resaco,0,'k12_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9841,'','".AddSlashes(pg_result($resaco,0,'k12_dtcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9843,'','".AddSlashes(pg_result($resaco,0,'k12_vlrcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9844,'','".AddSlashes(pg_result($resaco,0,'k12_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9845,'','".AddSlashes(pg_result($resaco,0,'k12_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1689,9847,'','".AddSlashes(pg_result($resaco,0,'k12_horainc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k12_codcorcheque=null) { 
      $this->atualizacampos();
     $sql = " update corcheque set ";
     $virgula = "";
     if(trim($this->k12_codcorcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_codcorcheque"])){ 
       $sql  .= $virgula." k12_codcorcheque = $this->k12_codcorcheque ";
       $virgula = ",";
       if(trim($this->k12_codcorcheque) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k12_codcorcheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_banco"])){ 
       $sql  .= $virgula." k12_banco = $this->k12_banco ";
       $virgula = ",";
       if(trim($this->k12_banco) == null ){ 
         $this->erro_sql = " Campo Código do Banco nao Informado.";
         $this->erro_campo = "k12_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_agencia"])){ 
       $sql  .= $virgula." k12_agencia = '$this->k12_agencia' ";
       $virgula = ",";
       if(trim($this->k12_agencia) == null ){ 
         $this->erro_sql = " Campo Agência nao Informado.";
         $this->erro_campo = "k12_agencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_nominal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_nominal"])){ 
       $sql  .= $virgula." k12_nominal = '$this->k12_nominal' ";
       $virgula = ",";
       if(trim($this->k12_nominal) == null ){ 
         $this->erro_sql = " Campo Nominal nao Informado.";
         $this->erro_campo = "k12_nominal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_numero"])){ 
       $sql  .= $virgula." k12_numero = '$this->k12_numero' ";
       $virgula = ",";
       if(trim($this->k12_numero) == null ){ 
         $this->erro_sql = " Campo Número do Cheque nao Informado.";
         $this->erro_campo = "k12_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_dtcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_dtcheque_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k12_dtcheque_dia"] !="") ){ 
       $sql  .= $virgula." k12_dtcheque = '$this->k12_dtcheque' ";
       $virgula = ",";
       if(trim($this->k12_dtcheque) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k12_dtcheque_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k12_dtcheque_dia"])){ 
         $sql  .= $virgula." k12_dtcheque = null ";
         $virgula = ",";
         if(trim($this->k12_dtcheque) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k12_dtcheque_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k12_vlrcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_vlrcheque"])){ 
       $sql  .= $virgula." k12_vlrcheque = $this->k12_vlrcheque ";
       $virgula = ",";
       if(trim($this->k12_vlrcheque) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k12_vlrcheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_usuario"])){ 
       $sql  .= $virgula." k12_usuario = $this->k12_usuario ";
       $virgula = ",";
       if(trim($this->k12_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "k12_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_dtinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_dtinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k12_dtinc_dia"] !="") ){ 
       $sql  .= $virgula." k12_dtinc = '$this->k12_dtinc' ";
       $virgula = ",";
       if(trim($this->k12_dtinc) == null ){ 
         $this->erro_sql = " Campo Data da inclusão nao Informado.";
         $this->erro_campo = "k12_dtinc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k12_dtinc_dia"])){ 
         $sql  .= $virgula." k12_dtinc = null ";
         $virgula = ",";
         if(trim($this->k12_dtinc) == null ){ 
           $this->erro_sql = " Campo Data da inclusão nao Informado.";
           $this->erro_campo = "k12_dtinc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k12_horainc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_horainc"])){ 
       $sql  .= $virgula." k12_horainc = '$this->k12_horainc' ";
       $virgula = ",";
       if(trim($this->k12_horainc) == null ){ 
         $this->erro_sql = " Campo Hora da Inclusão nao Informado.";
         $this->erro_campo = "k12_horainc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k12_codcorcheque!=null){
       $sql .= " k12_codcorcheque = $this->k12_codcorcheque";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k12_codcorcheque));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9835,'$this->k12_codcorcheque','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_codcorcheque"]))
           $resac = db_query("insert into db_acount values($acount,1689,9835,'".AddSlashes(pg_result($resaco,$conresaco,'k12_codcorcheque'))."','$this->k12_codcorcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_banco"]))
           $resac = db_query("insert into db_acount values($acount,1689,9836,'".AddSlashes(pg_result($resaco,$conresaco,'k12_banco'))."','$this->k12_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_agencia"]))
           $resac = db_query("insert into db_acount values($acount,1689,9837,'".AddSlashes(pg_result($resaco,$conresaco,'k12_agencia'))."','$this->k12_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_nominal"]))
           $resac = db_query("insert into db_acount values($acount,1689,9838,'".AddSlashes(pg_result($resaco,$conresaco,'k12_nominal'))."','$this->k12_nominal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_numero"]))
           $resac = db_query("insert into db_acount values($acount,1689,9839,'".AddSlashes(pg_result($resaco,$conresaco,'k12_numero'))."','$this->k12_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_dtcheque"]))
           $resac = db_query("insert into db_acount values($acount,1689,9841,'".AddSlashes(pg_result($resaco,$conresaco,'k12_dtcheque'))."','$this->k12_dtcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_vlrcheque"]))
           $resac = db_query("insert into db_acount values($acount,1689,9843,'".AddSlashes(pg_result($resaco,$conresaco,'k12_vlrcheque'))."','$this->k12_vlrcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1689,9844,'".AddSlashes(pg_result($resaco,$conresaco,'k12_usuario'))."','$this->k12_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_dtinc"]))
           $resac = db_query("insert into db_acount values($acount,1689,9845,'".AddSlashes(pg_result($resaco,$conresaco,'k12_dtinc'))."','$this->k12_dtinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_horainc"]))
           $resac = db_query("insert into db_acount values($acount,1689,9847,'".AddSlashes(pg_result($resaco,$conresaco,'k12_horainc'))."','$this->k12_horainc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "corcheque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_codcorcheque;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "corcheque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_codcorcheque;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_codcorcheque;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k12_codcorcheque=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k12_codcorcheque));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9835,'$k12_codcorcheque','E')");
         $resac = db_query("insert into db_acount values($acount,1689,9835,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_codcorcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9836,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9837,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9838,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_nominal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9839,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9841,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_dtcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9843,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_vlrcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9844,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9845,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1689,9847,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_horainc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from corcheque
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k12_codcorcheque != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_codcorcheque = $k12_codcorcheque ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "corcheque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k12_codcorcheque;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "corcheque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k12_codcorcheque;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k12_codcorcheque;
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
        $this->erro_sql   = "Record Vazio na Tabela:corcheque";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k12_codcorcheque=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from corcheque ";
     $sql .= "      inner join bancos  on  bancos.codbco = corcheque.k12_banco";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = corcheque.k12_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_codcorcheque!=null ){
         $sql2 .= " where corcheque.k12_codcorcheque = $k12_codcorcheque "; 
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
   function sql_query_file ( $k12_codcorcheque=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from corcheque ";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_codcorcheque!=null ){
         $sql2 .= " where corcheque.k12_codcorcheque = $k12_codcorcheque "; 
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