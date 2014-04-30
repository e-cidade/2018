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

//MODULO: issqn
//CLASSE DA ENTIDADE isscalclog
class cl_isscalclog { 
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
   var $q47_codigo = 0; 
   var $q47_anousu = 0; 
   var $q47_data_dia = null; 
   var $q47_data_mes = null; 
   var $q47_data_ano = null; 
   var $q47_data = null; 
   var $q47_hora = null; 
   var $q47_usuario = 0; 
   var $q47_parcial = 'f'; 
   var $q47_quantaproc = 0; 
   var $q47_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q47_codigo = int4 = Código 
                 q47_anousu = int4 = Ano 
                 q47_data = date = Data 
                 q47_hora = char(5) = Hora 
                 q47_usuario = int4 = Cod. Usuário 
                 q47_parcial = bool = Parcial 
                 q47_quantaproc = int4 = N° registro processados 
                 q47_tipo = int4 = Codigo do calculo 
                 ";
   //funcao construtor da classe 
   function cl_isscalclog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isscalclog"); 
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
       $this->q47_codigo = ($this->q47_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_codigo"]:$this->q47_codigo);
       $this->q47_anousu = ($this->q47_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_anousu"]:$this->q47_anousu);
       if($this->q47_data == ""){
         $this->q47_data_dia = ($this->q47_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_data_dia"]:$this->q47_data_dia);
         $this->q47_data_mes = ($this->q47_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_data_mes"]:$this->q47_data_mes);
         $this->q47_data_ano = ($this->q47_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_data_ano"]:$this->q47_data_ano);
         if($this->q47_data_dia != ""){
            $this->q47_data = $this->q47_data_ano."-".$this->q47_data_mes."-".$this->q47_data_dia;
         }
       }
       $this->q47_hora = ($this->q47_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_hora"]:$this->q47_hora);
       $this->q47_usuario = ($this->q47_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_usuario"]:$this->q47_usuario);
       $this->q47_parcial = ($this->q47_parcial == "f"?@$GLOBALS["HTTP_POST_VARS"]["q47_parcial"]:$this->q47_parcial);
       $this->q47_quantaproc = ($this->q47_quantaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_quantaproc"]:$this->q47_quantaproc);
       $this->q47_tipo = ($this->q47_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_tipo"]:$this->q47_tipo);
     }else{
       $this->q47_codigo = ($this->q47_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q47_codigo"]:$this->q47_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q47_codigo){ 
      $this->atualizacampos();
     if($this->q47_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "q47_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q47_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "q47_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q47_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "q47_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q47_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "q47_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q47_parcial == null ){ 
       $this->erro_sql = " Campo Parcial nao Informado.";
       $this->erro_campo = "q47_parcial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q47_quantaproc == null ){ 
       $this->erro_sql = " Campo N° registro processados nao Informado.";
       $this->erro_campo = "q47_quantaproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q47_tipo == null ){ 
       $this->erro_sql = " Campo Codigo do calculo nao Informado.";
       $this->erro_campo = "q47_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q47_codigo == "" || $q47_codigo == null ){
       $result = db_query("select nextval('isscalclog_q47_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isscalclog_q47_codigo_seq do campo: q47_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q47_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isscalclog_q47_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q47_codigo)){
         $this->erro_sql = " Campo q47_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q47_codigo = $q47_codigo; 
       }
     }
     if(($this->q47_codigo == null) || ($this->q47_codigo == "") ){ 
       $this->erro_sql = " Campo q47_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isscalclog(
                                       q47_codigo 
                                      ,q47_anousu 
                                      ,q47_data 
                                      ,q47_hora 
                                      ,q47_usuario 
                                      ,q47_parcial 
                                      ,q47_quantaproc 
                                      ,q47_tipo 
                       )
                values (
                                $this->q47_codigo 
                               ,$this->q47_anousu 
                               ,".($this->q47_data == "null" || $this->q47_data == ""?"null":"'".$this->q47_data."'")." 
                               ,'$this->q47_hora' 
                               ,$this->q47_usuario 
                               ,'$this->q47_parcial' 
                               ,$this->q47_quantaproc 
                               ,$this->q47_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "isscalclog ($this->q47_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "isscalclog já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "isscalclog ($this->q47_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q47_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q47_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9768,'$this->q47_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1675,9768,'','".AddSlashes(pg_result($resaco,0,'q47_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1675,9769,'','".AddSlashes(pg_result($resaco,0,'q47_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1675,9770,'','".AddSlashes(pg_result($resaco,0,'q47_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1675,9771,'','".AddSlashes(pg_result($resaco,0,'q47_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1675,9772,'','".AddSlashes(pg_result($resaco,0,'q47_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1675,9773,'','".AddSlashes(pg_result($resaco,0,'q47_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1675,9774,'','".AddSlashes(pg_result($resaco,0,'q47_quantaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1675,9798,'','".AddSlashes(pg_result($resaco,0,'q47_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q47_codigo=null) { 
      $this->atualizacampos();
     $sql = " update isscalclog set ";
     $virgula = "";
     if(trim($this->q47_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_codigo"])){ 
       $sql  .= $virgula." q47_codigo = $this->q47_codigo ";
       $virgula = ",";
       if(trim($this->q47_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q47_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q47_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_anousu"])){ 
       $sql  .= $virgula." q47_anousu = $this->q47_anousu ";
       $virgula = ",";
       if(trim($this->q47_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "q47_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q47_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q47_data_dia"] !="") ){ 
       $sql  .= $virgula." q47_data = '$this->q47_data' ";
       $virgula = ",";
       if(trim($this->q47_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "q47_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q47_data_dia"])){ 
         $sql  .= $virgula." q47_data = null ";
         $virgula = ",";
         if(trim($this->q47_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "q47_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q47_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_hora"])){ 
       $sql  .= $virgula." q47_hora = '$this->q47_hora' ";
       $virgula = ",";
       if(trim($this->q47_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "q47_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q47_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_usuario"])){ 
       $sql  .= $virgula." q47_usuario = $this->q47_usuario ";
       $virgula = ",";
       if(trim($this->q47_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "q47_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q47_parcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_parcial"])){ 
       $sql  .= $virgula." q47_parcial = '$this->q47_parcial' ";
       $virgula = ",";
       if(trim($this->q47_parcial) == null ){ 
         $this->erro_sql = " Campo Parcial nao Informado.";
         $this->erro_campo = "q47_parcial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q47_quantaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_quantaproc"])){ 
       $sql  .= $virgula." q47_quantaproc = $this->q47_quantaproc ";
       $virgula = ",";
       if(trim($this->q47_quantaproc) == null ){ 
         $this->erro_sql = " Campo N° registro processados nao Informado.";
         $this->erro_campo = "q47_quantaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q47_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q47_tipo"])){ 
       $sql  .= $virgula." q47_tipo = $this->q47_tipo ";
       $virgula = ",";
       if(trim($this->q47_tipo) == null ){ 
         $this->erro_sql = " Campo Codigo do calculo nao Informado.";
         $this->erro_campo = "q47_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q47_codigo!=null){
       $sql .= " q47_codigo = $this->q47_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q47_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9768,'$this->q47_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1675,9768,'".AddSlashes(pg_result($resaco,$conresaco,'q47_codigo'))."','$this->q47_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1675,9769,'".AddSlashes(pg_result($resaco,$conresaco,'q47_anousu'))."','$this->q47_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_data"]))
           $resac = db_query("insert into db_acount values($acount,1675,9770,'".AddSlashes(pg_result($resaco,$conresaco,'q47_data'))."','$this->q47_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_hora"]))
           $resac = db_query("insert into db_acount values($acount,1675,9771,'".AddSlashes(pg_result($resaco,$conresaco,'q47_hora'))."','$this->q47_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1675,9772,'".AddSlashes(pg_result($resaco,$conresaco,'q47_usuario'))."','$this->q47_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_parcial"]))
           $resac = db_query("insert into db_acount values($acount,1675,9773,'".AddSlashes(pg_result($resaco,$conresaco,'q47_parcial'))."','$this->q47_parcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_quantaproc"]))
           $resac = db_query("insert into db_acount values($acount,1675,9774,'".AddSlashes(pg_result($resaco,$conresaco,'q47_quantaproc'))."','$this->q47_quantaproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q47_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1675,9798,'".AddSlashes(pg_result($resaco,$conresaco,'q47_tipo'))."','$this->q47_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "isscalclog nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q47_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "isscalclog nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q47_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q47_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9768,'$q47_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1675,9768,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1675,9769,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1675,9770,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1675,9771,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1675,9772,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1675,9773,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1675,9774,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_quantaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1675,9798,'','".AddSlashes(pg_result($resaco,$iresaco,'q47_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isscalclog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q47_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q47_codigo = $q47_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "isscalclog nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q47_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "isscalclog nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q47_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:isscalclog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isscalclog ";
     $sql .= "      inner join cadcalc  on  cadcalc.q85_codigo = isscalclog.q47_tipo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = isscalclog.q47_usuario";
     $sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = cadcalc.q85_codven";
     $sql .= "      inner join forcaldesc  on  forcaldesc.q87_codigo = cadcalc.q85_forcal";
     $sql2 = "";
     if($dbwhere==""){
       if($q47_codigo!=null ){
         $sql2 .= " where isscalclog.q47_codigo = $q47_codigo "; 
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
   function sql_query_file ( $q47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isscalclog ";
     $sql2 = "";
     if($dbwhere==""){
       if($q47_codigo!=null ){
         $sql2 .= " where isscalclog.q47_codigo = $q47_codigo "; 
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
   function sql_query_inf ( $q47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isscalclog ";     
     $sql .= "      inner join isscalcloginscr  on  isscalclog.q47_codigo = isscalcloginscr.q48_isscalclog";
     $sql .= "      inner join isscadlogcalc  on  isscadlogcalc.q46_codigo = isscalcloginscr.q48_isscadlog";     
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = isscalclog.q47_usuario";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = isscalcloginscr.q48_inscr";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
          
     $sql2 = "";
     if($dbwhere==""){
       if($q47_codigo!=null ){
         $sql2 .= " where isscalclog.q47_codigo = $q47_codigo "; 
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