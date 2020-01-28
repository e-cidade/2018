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

//MODULO: Juridico
//CLASSE DA ENTIDADE processoforoinicial
class cl_processoforoinicial { 
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
   var $v71_sequencial = 0; 
   var $v71_id_usuario = 0; 
   var $v71_inicial = 0; 
   var $v71_processoforo = 0; 
   var $v71_data_dia = null; 
   var $v71_data_mes = null; 
   var $v71_data_ano = null; 
   var $v71_data = null; 
   var $v71_anulado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v71_sequencial = int4 = Código Sequencial 
                 v71_id_usuario = int4 = Id usuário 
                 v71_inicial = int4 = Inicial 
                 v71_processoforo = int4 = Processo foro 
                 v71_data = date = Data 
                 v71_anulado = bool = Anulado 
                 ";
   //funcao construtor da classe 
   function cl_processoforoinicial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processoforoinicial"); 
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
       $this->v71_sequencial = ($this->v71_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_sequencial"]:$this->v71_sequencial);
       $this->v71_id_usuario = ($this->v71_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_id_usuario"]:$this->v71_id_usuario);
       $this->v71_inicial = ($this->v71_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_inicial"]:$this->v71_inicial);
       $this->v71_processoforo = ($this->v71_processoforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_processoforo"]:$this->v71_processoforo);
       if($this->v71_data == ""){
         $this->v71_data_dia = ($this->v71_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_data_dia"]:$this->v71_data_dia);
         $this->v71_data_mes = ($this->v71_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_data_mes"]:$this->v71_data_mes);
         $this->v71_data_ano = ($this->v71_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_data_ano"]:$this->v71_data_ano);
         if($this->v71_data_dia != ""){
            $this->v71_data = $this->v71_data_ano."-".$this->v71_data_mes."-".$this->v71_data_dia;
         }
       }
       $this->v71_anulado = ($this->v71_anulado == "f"?@$GLOBALS["HTTP_POST_VARS"]["v71_anulado"]:$this->v71_anulado);
     }else{
       $this->v71_sequencial = ($this->v71_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v71_sequencial"]:$this->v71_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v71_sequencial){ 
      $this->atualizacampos();
     if($this->v71_id_usuario == null ){ 
       $this->erro_sql = " Campo Id usuário nao Informado.";
       $this->erro_campo = "v71_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v71_inicial == null ){ 
       $this->erro_sql = " Campo Inicial nao Informado.";
       $this->erro_campo = "v71_inicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v71_processoforo == null ){ 
       $this->erro_sql = " Campo Processo foro nao Informado.";
       $this->erro_campo = "v71_processoforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v71_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v71_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v71_anulado == null ){ 
       $this->erro_sql = " Campo Anulado nao Informado.";
       $this->erro_campo = "v71_anulado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v71_sequencial == "" || $v71_sequencial == null ){
       $result = db_query("select nextval('processoforoinicial_v71_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processoforoinicial_v71_sequencial_seq do campo: v71_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v71_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from processoforoinicial_v71_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v71_sequencial)){
         $this->erro_sql = " Campo v71_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v71_sequencial = $v71_sequencial; 
       }
     }
     if(($this->v71_sequencial == null) || ($this->v71_sequencial == "") ){ 
       $this->erro_sql = " Campo v71_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processoforoinicial(
                                       v71_sequencial 
                                      ,v71_id_usuario 
                                      ,v71_inicial 
                                      ,v71_processoforo 
                                      ,v71_data 
                                      ,v71_anulado 
                       )
                values (
                                $this->v71_sequencial 
                               ,$this->v71_id_usuario 
                               ,$this->v71_inicial 
                               ,$this->v71_processoforo 
                               ,".($this->v71_data == "null" || $this->v71_data == ""?"null":"'".$this->v71_data."'")." 
                               ,'$this->v71_anulado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "processoforoinicial ($this->v71_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "processoforoinicial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "processoforoinicial ($this->v71_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v71_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v71_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17349,'$this->v71_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3070,17349,'','".AddSlashes(pg_result($resaco,0,'v71_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3070,17350,'','".AddSlashes(pg_result($resaco,0,'v71_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3070,17351,'','".AddSlashes(pg_result($resaco,0,'v71_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3070,17352,'','".AddSlashes(pg_result($resaco,0,'v71_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3070,17353,'','".AddSlashes(pg_result($resaco,0,'v71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3070,17354,'','".AddSlashes(pg_result($resaco,0,'v71_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v71_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update processoforoinicial set ";
     $virgula = "";
     if(trim($this->v71_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v71_sequencial"])){ 
       $sql  .= $virgula." v71_sequencial = $this->v71_sequencial ";
       $virgula = ",";
       if(trim($this->v71_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "v71_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v71_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v71_id_usuario"])){ 
       $sql  .= $virgula." v71_id_usuario = $this->v71_id_usuario ";
       $virgula = ",";
       if(trim($this->v71_id_usuario) == null ){ 
         $this->erro_sql = " Campo Id usuário nao Informado.";
         $this->erro_campo = "v71_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v71_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v71_inicial"])){ 
       $sql  .= $virgula." v71_inicial = $this->v71_inicial ";
       $virgula = ",";
       if(trim($this->v71_inicial) == null ){ 
         $this->erro_sql = " Campo Inicial nao Informado.";
         $this->erro_campo = "v71_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v71_processoforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v71_processoforo"])){ 
       $sql  .= $virgula." v71_processoforo = $this->v71_processoforo ";
       $virgula = ",";
       if(trim($this->v71_processoforo) == null ){ 
         $this->erro_sql = " Campo Processo foro nao Informado.";
         $this->erro_campo = "v71_processoforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v71_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v71_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v71_data_dia"] !="") ){ 
       $sql  .= $virgula." v71_data = '$this->v71_data' ";
       $virgula = ",";
       if(trim($this->v71_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v71_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v71_data_dia"])){ 
         $sql  .= $virgula." v71_data = null ";
         $virgula = ",";
         if(trim($this->v71_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v71_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v71_anulado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v71_anulado"])){ 
       $sql  .= $virgula." v71_anulado = '$this->v71_anulado' ";
       $virgula = ",";
       if(trim($this->v71_anulado) == null ){ 
         $this->erro_sql = " Campo Anulado nao Informado.";
         $this->erro_campo = "v71_anulado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v71_sequencial!=null){
       $sql .= " v71_sequencial = $this->v71_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v71_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17349,'$this->v71_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v71_sequencial"]) || $this->v71_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3070,17349,'".AddSlashes(pg_result($resaco,$conresaco,'v71_sequencial'))."','$this->v71_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v71_id_usuario"]) || $this->v71_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3070,17350,'".AddSlashes(pg_result($resaco,$conresaco,'v71_id_usuario'))."','$this->v71_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v71_inicial"]) || $this->v71_inicial != "")
           $resac = db_query("insert into db_acount values($acount,3070,17351,'".AddSlashes(pg_result($resaco,$conresaco,'v71_inicial'))."','$this->v71_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v71_processoforo"]) || $this->v71_processoforo != "")
           $resac = db_query("insert into db_acount values($acount,3070,17352,'".AddSlashes(pg_result($resaco,$conresaco,'v71_processoforo'))."','$this->v71_processoforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v71_data"]) || $this->v71_data != "")
           $resac = db_query("insert into db_acount values($acount,3070,17353,'".AddSlashes(pg_result($resaco,$conresaco,'v71_data'))."','$this->v71_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v71_anulado"]) || $this->v71_anulado != "")
           $resac = db_query("insert into db_acount values($acount,3070,17354,'".AddSlashes(pg_result($resaco,$conresaco,'v71_anulado'))."','$this->v71_anulado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "processoforoinicial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v71_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "processoforoinicial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v71_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v71_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v71_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v71_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17349,'$v71_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3070,17349,'','".AddSlashes(pg_result($resaco,$iresaco,'v71_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3070,17350,'','".AddSlashes(pg_result($resaco,$iresaco,'v71_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3070,17351,'','".AddSlashes(pg_result($resaco,$iresaco,'v71_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3070,17352,'','".AddSlashes(pg_result($resaco,$iresaco,'v71_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3070,17353,'','".AddSlashes(pg_result($resaco,$iresaco,'v71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3070,17354,'','".AddSlashes(pg_result($resaco,$iresaco,'v71_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processoforoinicial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v71_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v71_sequencial = $v71_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "processoforoinicial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v71_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "processoforoinicial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v71_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v71_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processoforoinicial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v71_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoforoinicial ";
     $sql .= "      inner join inicial            on inicial.v50_inicial            = processoforoinicial.v71_inicial";
     $sql .= "      inner join db_usuarios        on db_usuarios.id_usuario         = processoforoinicial.v71_id_usuario";
     $sql .= "      inner join processoforo       on processoforo.v70_sequencial    = processoforoinicial.v71_processoforo";
     $sql .= "      inner join db_config          on db_config.codigo               = inicial.v50_instit";
     $sql .= "      inner join advog              on advog.v57_numcgm               = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  as a  on a.id_usuario                   = inicial.v50_id_login";
     $sql .= "      inner join localiza           on localiza.v54_codlocal          = inicial.v50_codlocal";
     $sql .= "      inner join db_usuarios  as b  on b.id_usuario                   = processoforo.v70_id_usuario";
     $sql .= "      inner join vara               on vara.v53_codvara               = processoforo.v70_vara";
     $sql .= "      left  join processoforomov    on processoforomov.v73_sequencial = processoforo.v70_processoforomov";
     $sql2 = "";
     if($dbwhere==""){
       if($v71_sequencial!=null ){
         $sql2 .= " where processoforoinicial.v71_sequencial = $v71_sequencial "; 
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
   function sql_query_file ( $v71_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoforoinicial ";
     $sql2 = "";
     if($dbwhere==""){
       if($v71_sequencial!=null ){
         $sql2 .= " where processoforoinicial.v71_sequencial = $v71_sequencial "; 
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