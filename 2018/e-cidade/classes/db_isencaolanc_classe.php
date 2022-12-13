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

//MODULO: tributario
//CLASSE DA ENTIDADE isencaolanc
class cl_isencaolanc { 
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
   var $v18_sequencial = 0; 
   var $v18_cadtipoitem = 0; 
   var $v18_isencao = 0; 
   var $v18_dtini_dia = null; 
   var $v18_dtini_mes = null; 
   var $v18_dtini_ano = null; 
   var $v18_dtini = null; 
   var $v18_dtfim_dia = null; 
   var $v18_dtfim_mes = null; 
   var $v18_dtfim_ano = null; 
   var $v18_dtfim = null; 
   var $v18_tipovalor = 0; 
   var $v18_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v18_sequencial = int4 = Codigo do lançamento 
                 v18_cadtipoitem = int4 = Codigo 
                 v18_isencao = int4 = Codigo da isenção 
                 v18_dtini = date = Data de inicio 
                 v18_dtfim = date = Data final 
                 v18_tipovalor = int4 = Tipo de valor 
                 v18_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_isencaolanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isencaolanc"); 
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
       $this->v18_sequencial = ($this->v18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_sequencial"]:$this->v18_sequencial);
       $this->v18_cadtipoitem = ($this->v18_cadtipoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_cadtipoitem"]:$this->v18_cadtipoitem);
       $this->v18_isencao = ($this->v18_isencao == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_isencao"]:$this->v18_isencao);
       if($this->v18_dtini == ""){
         $this->v18_dtini_dia = ($this->v18_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_dtini_dia"]:$this->v18_dtini_dia);
         $this->v18_dtini_mes = ($this->v18_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_dtini_mes"]:$this->v18_dtini_mes);
         $this->v18_dtini_ano = ($this->v18_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_dtini_ano"]:$this->v18_dtini_ano);
         if($this->v18_dtini_dia != ""){
            $this->v18_dtini = $this->v18_dtini_ano."-".$this->v18_dtini_mes."-".$this->v18_dtini_dia;
         }
       }
       if($this->v18_dtfim == ""){
         $this->v18_dtfim_dia = ($this->v18_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_dtfim_dia"]:$this->v18_dtfim_dia);
         $this->v18_dtfim_mes = ($this->v18_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_dtfim_mes"]:$this->v18_dtfim_mes);
         $this->v18_dtfim_ano = ($this->v18_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_dtfim_ano"]:$this->v18_dtfim_ano);
         if($this->v18_dtfim_dia != ""){
            $this->v18_dtfim = $this->v18_dtfim_ano."-".$this->v18_dtfim_mes."-".$this->v18_dtfim_dia;
         }
       }
       $this->v18_tipovalor = ($this->v18_tipovalor == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_tipovalor"]:$this->v18_tipovalor);
       $this->v18_valor = ($this->v18_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_valor"]:$this->v18_valor);
     }else{
       $this->v18_sequencial = ($this->v18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v18_sequencial"]:$this->v18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v18_sequencial){ 
      $this->atualizacampos();
     if($this->v18_cadtipoitem == null ){ 
       $this->erro_sql = " Campo Codigo nao Informado.";
       $this->erro_campo = "v18_cadtipoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v18_isencao == null ){ 
       $this->erro_sql = " Campo Codigo da isenção nao Informado.";
       $this->erro_campo = "v18_isencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v18_dtini == null ){ 
       $this->erro_sql = " Campo Data de inicio nao Informado.";
       $this->erro_campo = "v18_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v18_dtfim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "v18_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v18_tipovalor == null ){ 
       $this->erro_sql = " Campo Tipo de valor nao Informado.";
       $this->erro_campo = "v18_tipovalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v18_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "v18_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v18_sequencial == "" || $v18_sequencial == null ){
       $result = db_query("select nextval('isencaolanc_v18_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isencaolanc_v18_sequencial_seq do campo: v18_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v18_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isencaolanc_v18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v18_sequencial)){
         $this->erro_sql = " Campo v18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v18_sequencial = $v18_sequencial; 
       }
     }
     if(($this->v18_sequencial == null) || ($this->v18_sequencial == "") ){ 
       $this->erro_sql = " Campo v18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isencaolanc(
                                       v18_sequencial 
                                      ,v18_cadtipoitem 
                                      ,v18_isencao 
                                      ,v18_dtini 
                                      ,v18_dtfim 
                                      ,v18_tipovalor 
                                      ,v18_valor 
                       )
                values (
                                $this->v18_sequencial 
                               ,$this->v18_cadtipoitem 
                               ,$this->v18_isencao 
                               ,".($this->v18_dtini == "null" || $this->v18_dtini == ""?"null":"'".$this->v18_dtini."'")." 
                               ,".($this->v18_dtfim == "null" || $this->v18_dtfim == ""?"null":"'".$this->v18_dtfim."'")." 
                               ,$this->v18_tipovalor 
                               ,$this->v18_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lancamentos das isenções ($this->v18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lancamentos das isenções já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lancamentos das isenções ($this->v18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9938,'$this->v18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1708,9938,'','".AddSlashes(pg_result($resaco,0,'v18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1708,9958,'','".AddSlashes(pg_result($resaco,0,'v18_cadtipoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1708,9959,'','".AddSlashes(pg_result($resaco,0,'v18_isencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1708,9960,'','".AddSlashes(pg_result($resaco,0,'v18_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1708,9961,'','".AddSlashes(pg_result($resaco,0,'v18_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1708,9962,'','".AddSlashes(pg_result($resaco,0,'v18_tipovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1708,9963,'','".AddSlashes(pg_result($resaco,0,'v18_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v18_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update isencaolanc set ";
     $virgula = "";
     if(trim($this->v18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v18_sequencial"])){ 
       $sql  .= $virgula." v18_sequencial = $this->v18_sequencial ";
       $virgula = ",";
       if(trim($this->v18_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo do lançamento nao Informado.";
         $this->erro_campo = "v18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v18_cadtipoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v18_cadtipoitem"])){ 
       $sql  .= $virgula." v18_cadtipoitem = $this->v18_cadtipoitem ";
       $virgula = ",";
       if(trim($this->v18_cadtipoitem) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "v18_cadtipoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v18_isencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v18_isencao"])){ 
       $sql  .= $virgula." v18_isencao = $this->v18_isencao ";
       $virgula = ",";
       if(trim($this->v18_isencao) == null ){ 
         $this->erro_sql = " Campo Codigo da isenção nao Informado.";
         $this->erro_campo = "v18_isencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v18_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v18_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v18_dtini_dia"] !="") ){ 
       $sql  .= $virgula." v18_dtini = '$this->v18_dtini' ";
       $virgula = ",";
       if(trim($this->v18_dtini) == null ){ 
         $this->erro_sql = " Campo Data de inicio nao Informado.";
         $this->erro_campo = "v18_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v18_dtini_dia"])){ 
         $sql  .= $virgula." v18_dtini = null ";
         $virgula = ",";
         if(trim($this->v18_dtini) == null ){ 
           $this->erro_sql = " Campo Data de inicio nao Informado.";
           $this->erro_campo = "v18_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v18_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v18_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v18_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." v18_dtfim = '$this->v18_dtfim' ";
       $virgula = ",";
       if(trim($this->v18_dtfim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "v18_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v18_dtfim_dia"])){ 
         $sql  .= $virgula." v18_dtfim = null ";
         $virgula = ",";
         if(trim($this->v18_dtfim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "v18_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v18_tipovalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v18_tipovalor"])){ 
       $sql  .= $virgula." v18_tipovalor = $this->v18_tipovalor ";
       $virgula = ",";
       if(trim($this->v18_tipovalor) == null ){ 
         $this->erro_sql = " Campo Tipo de valor nao Informado.";
         $this->erro_campo = "v18_tipovalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v18_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v18_valor"])){ 
       $sql  .= $virgula." v18_valor = $this->v18_valor ";
       $virgula = ",";
       if(trim($this->v18_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "v18_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v18_sequencial!=null){
       $sql .= " v18_sequencial = $this->v18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9938,'$this->v18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v18_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1708,9938,'".AddSlashes(pg_result($resaco,$conresaco,'v18_sequencial'))."','$this->v18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v18_cadtipoitem"]))
           $resac = db_query("insert into db_acount values($acount,1708,9958,'".AddSlashes(pg_result($resaco,$conresaco,'v18_cadtipoitem'))."','$this->v18_cadtipoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v18_isencao"]))
           $resac = db_query("insert into db_acount values($acount,1708,9959,'".AddSlashes(pg_result($resaco,$conresaco,'v18_isencao'))."','$this->v18_isencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v18_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1708,9960,'".AddSlashes(pg_result($resaco,$conresaco,'v18_dtini'))."','$this->v18_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v18_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1708,9961,'".AddSlashes(pg_result($resaco,$conresaco,'v18_dtfim'))."','$this->v18_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v18_tipovalor"]))
           $resac = db_query("insert into db_acount values($acount,1708,9962,'".AddSlashes(pg_result($resaco,$conresaco,'v18_tipovalor'))."','$this->v18_tipovalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v18_valor"]))
           $resac = db_query("insert into db_acount values($acount,1708,9963,'".AddSlashes(pg_result($resaco,$conresaco,'v18_valor'))."','$this->v18_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lancamentos das isenções nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lancamentos das isenções nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v18_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v18_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9938,'$v18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1708,9938,'','".AddSlashes(pg_result($resaco,$iresaco,'v18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1708,9958,'','".AddSlashes(pg_result($resaco,$iresaco,'v18_cadtipoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1708,9959,'','".AddSlashes(pg_result($resaco,$iresaco,'v18_isencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1708,9960,'','".AddSlashes(pg_result($resaco,$iresaco,'v18_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1708,9961,'','".AddSlashes(pg_result($resaco,$iresaco,'v18_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1708,9962,'','".AddSlashes(pg_result($resaco,$iresaco,'v18_tipovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1708,9963,'','".AddSlashes(pg_result($resaco,$iresaco,'v18_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isencaolanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v18_sequencial = $v18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lancamentos das isenções nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lancamentos das isenções nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:isencaolanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isencaolanc ";
     $sql .= "      inner join cadtipoitem  on  cadtipoitem.k09_sequencial = isencaolanc.v18_cadtipoitem";
     $sql .= "      inner join isencao  on  isencao.v10_sequencial = isencaolanc.v18_isencao";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = cadtipoitem.k09_cadtipo";
     $sql .= "      inner join cadtipoitemgrupo  on  cadtipoitemgrupo.k37_sequencial = cadtipoitem.k09_cadtipoitemgrupo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = isencao.v10_usuario";
     $sql .= "      inner join isencaotipo  on  isencaotipo.v11_sequencial = isencao.v10_isencaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($v18_sequencial!=null ){
         $sql2 .= " where isencaolanc.v18_sequencial = $v18_sequencial "; 
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
   function sql_query_file ( $v18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isencaolanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($v18_sequencial!=null ){
         $sql2 .= " where isencaolanc.v18_sequencial = $v18_sequencial "; 
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