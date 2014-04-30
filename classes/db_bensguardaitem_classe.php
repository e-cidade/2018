<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: patrim
//CLASSE DA ENTIDADE bensguardaitem
class cl_bensguardaitem { 
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
   var $t22_codigo = 0; 
   var $t22_bensguarda = 0; 
   var $t22_bem = 0; 
   var $t22_dtini_dia = null; 
   var $t22_dtini_mes = null; 
   var $t22_dtini_ano = null; 
   var $t22_dtini = null; 
   var $t22_dtfim_dia = null; 
   var $t22_dtfim_mes = null; 
   var $t22_dtfim_ano = null; 
   var $t22_dtfim = null; 
   var $t22_obs = null; 
   var $t22_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t22_codigo = int4 = Código Sequencial 
                 t22_bensguarda = int4 = Cod. Guarda 
                 t22_bem = int8 = Código do bem 
                 t22_dtini = date = Data Início 
                 t22_dtfim = date = Data Fim 
                 t22_obs = text = Observação 
                 t22_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_bensguardaitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensguardaitem"); 
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
       $this->t22_codigo = ($this->t22_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_codigo"]:$this->t22_codigo);
       $this->t22_bensguarda = ($this->t22_bensguarda == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_bensguarda"]:$this->t22_bensguarda);
       $this->t22_bem = ($this->t22_bem == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_bem"]:$this->t22_bem);
       if($this->t22_dtini == ""){
         $this->t22_dtini_dia = ($this->t22_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_dtini_dia"]:$this->t22_dtini_dia);
         $this->t22_dtini_mes = ($this->t22_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_dtini_mes"]:$this->t22_dtini_mes);
         $this->t22_dtini_ano = ($this->t22_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_dtini_ano"]:$this->t22_dtini_ano);
         if($this->t22_dtini_dia != ""){
            $this->t22_dtini = $this->t22_dtini_ano."-".$this->t22_dtini_mes."-".$this->t22_dtini_dia;
         }
       }
       if($this->t22_dtfim == ""){
         $this->t22_dtfim_dia = ($this->t22_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_dtfim_dia"]:$this->t22_dtfim_dia);
         $this->t22_dtfim_mes = ($this->t22_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_dtfim_mes"]:$this->t22_dtfim_mes);
         $this->t22_dtfim_ano = ($this->t22_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_dtfim_ano"]:$this->t22_dtfim_ano);
         if($this->t22_dtfim_dia != ""){
            $this->t22_dtfim = $this->t22_dtfim_ano."-".$this->t22_dtfim_mes."-".$this->t22_dtfim_dia;
         }
       }
       $this->t22_obs = ($this->t22_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_obs"]:$this->t22_obs);
       $this->t22_usuario = ($this->t22_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_usuario"]:$this->t22_usuario);
     }else{
       $this->t22_codigo = ($this->t22_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t22_codigo"]:$this->t22_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($t22_codigo){ 
      $this->atualizacampos();
     if($this->t22_bensguarda == null ){ 
       $this->erro_sql = " Campo Cod. Guarda nao Informado.";
       $this->erro_campo = "t22_bensguarda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t22_bem == null ){ 
       $this->erro_sql = " Campo Código do bem nao Informado.";
       $this->erro_campo = "t22_bem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t22_dtini == null ){ 
       $this->erro_sql = " Campo Data Início nao Informado.";
       $this->erro_campo = "t22_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t22_dtfim == null ){ 
       $this->t22_dtfim = "null";
     }
     if($this->t22_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t22_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t22_codigo == "" || $t22_codigo == null ){
       $result = db_query("select nextval('bensguardaitem_t22_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensguardaitem_t22_codigo_seq do campo: t22_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t22_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensguardaitem_t22_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $t22_codigo)){
         $this->erro_sql = " Campo t22_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t22_codigo = $t22_codigo; 
       }
     }
     if(($this->t22_codigo == null) || ($this->t22_codigo == "") ){ 
       $this->erro_sql = " Campo t22_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensguardaitem(
                                       t22_codigo 
                                      ,t22_bensguarda 
                                      ,t22_bem 
                                      ,t22_dtini 
                                      ,t22_dtfim 
                                      ,t22_obs 
                                      ,t22_usuario 
                       )
                values (
                                $this->t22_codigo 
                               ,$this->t22_bensguarda 
                               ,$this->t22_bem 
                               ,".($this->t22_dtini == "null" || $this->t22_dtini == ""?"null":"'".$this->t22_dtini."'")." 
                               ,".($this->t22_dtfim == "null" || $this->t22_dtfim == ""?"null":"'".$this->t22_dtfim."'")." 
                               ,'$this->t22_obs' 
                               ,$this->t22_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "bens que estão sob determinada guarda ($this->t22_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "bens que estão sob determinada guarda já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "bens que estão sob determinada guarda ($this->t22_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t22_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t22_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8958,'$this->t22_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1534,8958,'','".AddSlashes(pg_result($resaco,0,'t22_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1534,8959,'','".AddSlashes(pg_result($resaco,0,'t22_bensguarda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1534,8960,'','".AddSlashes(pg_result($resaco,0,'t22_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1534,8961,'','".AddSlashes(pg_result($resaco,0,'t22_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1534,8962,'','".AddSlashes(pg_result($resaco,0,'t22_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1534,8963,'','".AddSlashes(pg_result($resaco,0,'t22_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1534,8964,'','".AddSlashes(pg_result($resaco,0,'t22_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t22_codigo=null) { 
      $this->atualizacampos();
     $sql = " update bensguardaitem set ";
     $virgula = "";
     if(trim($this->t22_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t22_codigo"])){ 
       $sql  .= $virgula." t22_codigo = $this->t22_codigo ";
       $virgula = ",";
       if(trim($this->t22_codigo) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "t22_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t22_bensguarda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t22_bensguarda"])){ 
       $sql  .= $virgula." t22_bensguarda = $this->t22_bensguarda ";
       $virgula = ",";
       if(trim($this->t22_bensguarda) == null ){ 
         $this->erro_sql = " Campo Cod. Guarda nao Informado.";
         $this->erro_campo = "t22_bensguarda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t22_bem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t22_bem"])){ 
       $sql  .= $virgula." t22_bem = $this->t22_bem ";
       $virgula = ",";
       if(trim($this->t22_bem) == null ){ 
         $this->erro_sql = " Campo Código do bem nao Informado.";
         $this->erro_campo = "t22_bem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t22_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t22_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t22_dtini_dia"] !="") ){ 
       $sql  .= $virgula." t22_dtini = '$this->t22_dtini' ";
       $virgula = ",";
       if(trim($this->t22_dtini) == null ){ 
         $this->erro_sql = " Campo Data Início nao Informado.";
         $this->erro_campo = "t22_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t22_dtini_dia"])){ 
         $sql  .= $virgula." t22_dtini = null ";
         $virgula = ",";
         if(trim($this->t22_dtini) == null ){ 
           $this->erro_sql = " Campo Data Início nao Informado.";
           $this->erro_campo = "t22_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t22_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t22_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t22_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." t22_dtfim = '$this->t22_dtfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t22_dtfim_dia"])){ 
         $sql  .= $virgula." t22_dtfim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->t22_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t22_obs"])){ 
       $sql  .= $virgula." t22_obs = '$this->t22_obs' ";
       $virgula = ",";
     }
     if(trim($this->t22_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t22_usuario"])){ 
       $sql  .= $virgula." t22_usuario = $this->t22_usuario ";
       $virgula = ",";
       if(trim($this->t22_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t22_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t22_codigo!=null){
       $sql .= " t22_codigo = $this->t22_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t22_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8958,'$this->t22_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t22_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1534,8958,'".AddSlashes(pg_result($resaco,$conresaco,'t22_codigo'))."','$this->t22_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t22_bensguarda"]))
           $resac = db_query("insert into db_acount values($acount,1534,8959,'".AddSlashes(pg_result($resaco,$conresaco,'t22_bensguarda'))."','$this->t22_bensguarda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t22_bem"]))
           $resac = db_query("insert into db_acount values($acount,1534,8960,'".AddSlashes(pg_result($resaco,$conresaco,'t22_bem'))."','$this->t22_bem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t22_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1534,8961,'".AddSlashes(pg_result($resaco,$conresaco,'t22_dtini'))."','$this->t22_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t22_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1534,8962,'".AddSlashes(pg_result($resaco,$conresaco,'t22_dtfim'))."','$this->t22_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t22_obs"]))
           $resac = db_query("insert into db_acount values($acount,1534,8963,'".AddSlashes(pg_result($resaco,$conresaco,'t22_obs'))."','$this->t22_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t22_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1534,8964,'".AddSlashes(pg_result($resaco,$conresaco,'t22_usuario'))."','$this->t22_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bens que estão sob determinada guarda nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t22_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bens que estão sob determinada guarda nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t22_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t22_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8958,'$t22_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1534,8958,'','".AddSlashes(pg_result($resaco,$iresaco,'t22_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1534,8959,'','".AddSlashes(pg_result($resaco,$iresaco,'t22_bensguarda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1534,8960,'','".AddSlashes(pg_result($resaco,$iresaco,'t22_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1534,8961,'','".AddSlashes(pg_result($resaco,$iresaco,'t22_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1534,8962,'','".AddSlashes(pg_result($resaco,$iresaco,'t22_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1534,8963,'','".AddSlashes(pg_result($resaco,$iresaco,'t22_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1534,8964,'','".AddSlashes(pg_result($resaco,$iresaco,'t22_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensguardaitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t22_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t22_codigo = $t22_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bens que estão sob determinada guarda nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t22_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bens que estão sob determinada guarda nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t22_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensguardaitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t22_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguardaitem ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = bensguardaitem.t22_usuario";
     $sql .= "      inner join bens  on  bens.t52_bem = bensguardaitem.t22_bem";
     $sql .= "      inner join bensguarda  on  bensguarda.t21_codigo = bensguardaitem.t22_bensguarda";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = bensguarda.t21_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($t22_codigo!=null ){
         $sql2 .= " where bensguardaitem.t22_codigo = $t22_codigo "; 
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
   function sql_query_dev ( $t22_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguardaitem ";
     $sql .= "      inner join db_usuarios        on  db_usuarios.id_usuario           = bensguardaitem.t22_usuario";
     $sql .= "      inner join bens               on  bens.t52_bem                     = bensguardaitem.t22_bem";
     $sql .= "      inner join bensguarda         on  bensguarda.t21_codigo            = bensguardaitem.t22_bensguarda";
     $sql .= "      inner join cgm                on  cgm.z01_numcgm                   = bens.t52_numcgm";
     $sql .= "      inner join db_depart          on  db_depart.coddepto               = bens.t52_depart";
     $sql .= "      inner join clabens            on  clabens.t64_codcla               = bens.t52_codcla";
     $sql .= "      inner join cgm  as a          on  a.z01_numcgm                     = bensguarda.t21_numcgm";
     $sql .= "      inner join bensplaca          on  bensplaca.t41_bem                = bens.t52_bem";
     $sql .= "      left  join bensguardaitemdev  on  bensguardaitemdev.t23_guardaitem = bensguardaitem.t22_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($t22_codigo!=null ){
         $sql2 .= " where bensguardaitem.t22_codigo = $t22_codigo "; 
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
   function sql_query_file ( $t22_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguardaitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($t22_codigo!=null ){
         $sql2 .= " where bensguardaitem.t22_codigo = $t22_codigo "; 
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