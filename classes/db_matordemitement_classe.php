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

//MODULO: material
//CLASSE DA ENTIDADE matordemitement
class cl_matordemitement { 
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
   var $m54_sequencial = 0; 
   var $m54_codmatordemitem = 0; 
   var $m54_codpcmater = 0; 
   var $m54_codmatmater = 0; 
   var $m54_quantidade = 0; 
   var $m54_valor_unitario = 0; 
   var $m54_quantmulti = 0; 
   var $m54_codmatunid = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m54_sequencial = int8 = Codigo Sequencial do registro 
                 m54_codmatordemitem = int8 = Código sequencial do lançamento 
                 m54_codpcmater = int4 = Código do Material 
                 m54_codmatmater = int8 = Código do material 
                 m54_quantidade = float8 = Quant. 
                 m54_valor_unitario = float4 = Valor Unitario 
                 m54_quantmulti = float8 = Quant. Multi. 
                 m54_codmatunid = int8 = Código da unidade 
                 ";
   //funcao construtor da classe 
   function cl_matordemitement() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matordemitement"); 
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
       $this->m54_sequencial = ($this->m54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_sequencial"]:$this->m54_sequencial);
       $this->m54_codmatordemitem = ($this->m54_codmatordemitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_codmatordemitem"]:$this->m54_codmatordemitem);
       $this->m54_codpcmater = ($this->m54_codpcmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_codpcmater"]:$this->m54_codpcmater);
       $this->m54_codmatmater = ($this->m54_codmatmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_codmatmater"]:$this->m54_codmatmater);
       $this->m54_quantidade = ($this->m54_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_quantidade"]:$this->m54_quantidade);
       $this->m54_valor_unitario = ($this->m54_valor_unitario == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_valor_unitario"]:$this->m54_valor_unitario);
       $this->m54_quantmulti = ($this->m54_quantmulti == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_quantmulti"]:$this->m54_quantmulti);
       $this->m54_codmatunid = ($this->m54_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_codmatunid"]:$this->m54_codmatunid);
     }else{
       $this->m54_sequencial = ($this->m54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m54_sequencial"]:$this->m54_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m54_sequencial){ 
      $this->atualizacampos();
     if($this->m54_codmatordemitem == null ){ 
       $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
       $this->erro_campo = "m54_codmatordemitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m54_codpcmater == null ){ 
       $this->erro_sql = " Campo Código do Material nao Informado.";
       $this->erro_campo = "m54_codpcmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m54_codmatmater == null ){ 
       $this->erro_sql = " Campo Código do material nao Informado.";
       $this->erro_campo = "m54_codmatmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m54_quantidade == null ){ 
       $this->erro_sql = " Campo Quant. nao Informado.";
       $this->erro_campo = "m54_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m54_valor_unitario == null ){ 
       $this->erro_sql = " Campo Valor Unitario nao Informado.";
       $this->erro_campo = "m54_valor_unitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m54_quantmulti == null ){ 
       $this->erro_sql = " Campo Quant. Multi. nao Informado.";
       $this->erro_campo = "m54_quantmulti";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m54_codmatunid == null ){ 
       $this->erro_sql = " Campo Código da unidade nao Informado.";
       $this->erro_campo = "m54_codmatunid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m54_sequencial == "" || $m54_sequencial == null ){
       $result = db_query("select nextval('matordemitement_m54_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matordemitement_m54_sequencial_seq do campo: m54_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m54_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matordemitement_m54_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m54_sequencial)){
         $this->erro_sql = " Campo m54_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m54_sequencial = $m54_sequencial; 
       }
     }
     if(($this->m54_sequencial == null) || ($this->m54_sequencial == "") ){ 
       $this->erro_sql = " Campo m54_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matordemitement(
                                       m54_sequencial 
                                      ,m54_codmatordemitem 
                                      ,m54_codpcmater 
                                      ,m54_codmatmater 
                                      ,m54_quantidade 
                                      ,m54_valor_unitario 
                                      ,m54_quantmulti 
                                      ,m54_codmatunid 
                       )
                values (
                                $this->m54_sequencial 
                               ,$this->m54_codmatordemitem 
                               ,$this->m54_codpcmater 
                               ,$this->m54_codmatmater 
                               ,$this->m54_quantidade 
                               ,$this->m54_valor_unitario 
                               ,$this->m54_quantmulti 
                               ,$this->m54_codmatunid 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matordemitement ($this->m54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matordemitement já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matordemitement ($this->m54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m54_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m54_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6824,'$this->m54_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1119,6824,'','".AddSlashes(pg_result($resaco,0,'m54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1119,6825,'','".AddSlashes(pg_result($resaco,0,'m54_codmatordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1119,6826,'','".AddSlashes(pg_result($resaco,0,'m54_codpcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1119,6827,'','".AddSlashes(pg_result($resaco,0,'m54_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1119,6828,'','".AddSlashes(pg_result($resaco,0,'m54_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1119,6833,'','".AddSlashes(pg_result($resaco,0,'m54_valor_unitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1119,7020,'','".AddSlashes(pg_result($resaco,0,'m54_quantmulti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1119,7019,'','".AddSlashes(pg_result($resaco,0,'m54_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m54_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matordemitement set ";
     $virgula = "";
     if(trim($this->m54_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_sequencial"])){ 
       $sql  .= $virgula." m54_sequencial = $this->m54_sequencial ";
       $virgula = ",";
       if(trim($this->m54_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial do registro nao Informado.";
         $this->erro_campo = "m54_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m54_codmatordemitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_codmatordemitem"])){ 
       $sql  .= $virgula." m54_codmatordemitem = $this->m54_codmatordemitem ";
       $virgula = ",";
       if(trim($this->m54_codmatordemitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m54_codmatordemitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m54_codpcmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_codpcmater"])){ 
       $sql  .= $virgula." m54_codpcmater = $this->m54_codpcmater ";
       $virgula = ",";
       if(trim($this->m54_codpcmater) == null ){ 
         $this->erro_sql = " Campo Código do Material nao Informado.";
         $this->erro_campo = "m54_codpcmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m54_codmatmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_codmatmater"])){ 
       $sql  .= $virgula." m54_codmatmater = $this->m54_codmatmater ";
       $virgula = ",";
       if(trim($this->m54_codmatmater) == null ){ 
         $this->erro_sql = " Campo Código do material nao Informado.";
         $this->erro_campo = "m54_codmatmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m54_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_quantidade"])){ 
       $sql  .= $virgula." m54_quantidade = $this->m54_quantidade ";
       $virgula = ",";
       if(trim($this->m54_quantidade) == null ){ 
         $this->erro_sql = " Campo Quant. nao Informado.";
         $this->erro_campo = "m54_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m54_valor_unitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_valor_unitario"])){ 
       $sql  .= $virgula." m54_valor_unitario = $this->m54_valor_unitario ";
       $virgula = ",";
       if(trim($this->m54_valor_unitario) == null ){ 
         $this->erro_sql = " Campo Valor Unitario nao Informado.";
         $this->erro_campo = "m54_valor_unitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m54_quantmulti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_quantmulti"])){ 
       $sql  .= $virgula." m54_quantmulti = $this->m54_quantmulti ";
       $virgula = ",";
       if(trim($this->m54_quantmulti) == null ){ 
         $this->erro_sql = " Campo Quant. Multi. nao Informado.";
         $this->erro_campo = "m54_quantmulti";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m54_codmatunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m54_codmatunid"])){ 
       $sql  .= $virgula." m54_codmatunid = $this->m54_codmatunid ";
       $virgula = ",";
       if(trim($this->m54_codmatunid) == null ){ 
         $this->erro_sql = " Campo Código da unidade nao Informado.";
         $this->erro_campo = "m54_codmatunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m54_sequencial!=null){
       $sql .= " m54_sequencial = $this->m54_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m54_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6824,'$this->m54_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1119,6824,'".AddSlashes(pg_result($resaco,$conresaco,'m54_sequencial'))."','$this->m54_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_codmatordemitem"]))
           $resac = db_query("insert into db_acount values($acount,1119,6825,'".AddSlashes(pg_result($resaco,$conresaco,'m54_codmatordemitem'))."','$this->m54_codmatordemitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_codpcmater"]))
           $resac = db_query("insert into db_acount values($acount,1119,6826,'".AddSlashes(pg_result($resaco,$conresaco,'m54_codpcmater'))."','$this->m54_codpcmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_codmatmater"]))
           $resac = db_query("insert into db_acount values($acount,1119,6827,'".AddSlashes(pg_result($resaco,$conresaco,'m54_codmatmater'))."','$this->m54_codmatmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_quantidade"]))
           $resac = db_query("insert into db_acount values($acount,1119,6828,'".AddSlashes(pg_result($resaco,$conresaco,'m54_quantidade'))."','$this->m54_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_valor_unitario"]))
           $resac = db_query("insert into db_acount values($acount,1119,6833,'".AddSlashes(pg_result($resaco,$conresaco,'m54_valor_unitario'))."','$this->m54_valor_unitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_quantmulti"]))
           $resac = db_query("insert into db_acount values($acount,1119,7020,'".AddSlashes(pg_result($resaco,$conresaco,'m54_quantmulti'))."','$this->m54_quantmulti',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m54_codmatunid"]))
           $resac = db_query("insert into db_acount values($acount,1119,7019,'".AddSlashes(pg_result($resaco,$conresaco,'m54_codmatunid'))."','$this->m54_codmatunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matordemitement nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matordemitement nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m54_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m54_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6824,'$m54_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1119,6824,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1119,6825,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_codmatordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1119,6826,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_codpcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1119,6827,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1119,6828,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1119,6833,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_valor_unitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1119,7020,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_quantmulti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1119,7019,'','".AddSlashes(pg_result($resaco,$iresaco,'m54_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matordemitement
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m54_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m54_sequencial = $m54_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matordemitement nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matordemitement nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m54_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matordemitement";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitement ";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = matordemitement.m54_codpcmater";
     $sql .= "      inner join matordemitem  on  matordemitem.m52_codlanc = matordemitement.m54_codmatordemitem";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matordemitement.m54_codmatmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matordemitement.m54_codmatunid";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join matordem  as a on   a.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join matunid  as b on   b.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($m54_sequencial!=null ){
         $sql2 .= " where matordemitement.m54_sequencial = $m54_sequencial "; 
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
   function sql_query_file ( $m54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitement ";
     $sql2 = "";
     if($dbwhere==""){
       if($m54_sequencial!=null ){
         $sql2 .= " where matordemitement.m54_sequencial = $m54_sequencial "; 
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