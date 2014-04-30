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

//MODULO: agua
//CLASSE DA ENTIDADE aguaisencao
class cl_aguaisencao { 
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
   var $x10_matric = 0; 
   var $x10_obs = null; 
   var $x10_codisencao = 0; 
   var $x10_codisencaotipo = 0; 
   var $x10_dtini_dia = null; 
   var $x10_dtini_mes = null; 
   var $x10_dtini_ano = null; 
   var $x10_dtini = null; 
   var $x10_dtfim_dia = null; 
   var $x10_dtfim_mes = null; 
   var $x10_dtfim_ano = null; 
   var $x10_dtfim = null; 
   var $x10_processo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x10_matric = int4 = Matrícula 
                 x10_obs = text = Observações 
                 x10_codisencao = int4 = Codigo Isencao 
                 x10_codisencaotipo = int4 = Tipo se Isencao 
                 x10_dtini = date = Inicio 
                 x10_dtfim = date = Fim 
                 x10_processo = int4 = Processo 
                 ";
   //funcao construtor da classe 
   function cl_aguaisencao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaisencao"); 
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
       $this->x10_matric = ($this->x10_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_matric"]:$this->x10_matric);
       $this->x10_obs = ($this->x10_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_obs"]:$this->x10_obs);
       $this->x10_codisencao = ($this->x10_codisencao == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_codisencao"]:$this->x10_codisencao);
       $this->x10_codisencaotipo = ($this->x10_codisencaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_codisencaotipo"]:$this->x10_codisencaotipo);
       if($this->x10_dtini == ""){
         $this->x10_dtini_dia = ($this->x10_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_dtini_dia"]:$this->x10_dtini_dia);
         $this->x10_dtini_mes = ($this->x10_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_dtini_mes"]:$this->x10_dtini_mes);
         $this->x10_dtini_ano = ($this->x10_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_dtini_ano"]:$this->x10_dtini_ano);
         if($this->x10_dtini_dia != ""){
            $this->x10_dtini = $this->x10_dtini_ano."-".$this->x10_dtini_mes."-".$this->x10_dtini_dia;
         }
       }
       if($this->x10_dtfim == ""){
         $this->x10_dtfim_dia = ($this->x10_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_dtfim_dia"]:$this->x10_dtfim_dia);
         $this->x10_dtfim_mes = ($this->x10_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_dtfim_mes"]:$this->x10_dtfim_mes);
         $this->x10_dtfim_ano = ($this->x10_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_dtfim_ano"]:$this->x10_dtfim_ano);
         if($this->x10_dtfim_dia != ""){
            $this->x10_dtfim = $this->x10_dtfim_ano."-".$this->x10_dtfim_mes."-".$this->x10_dtfim_dia;
         }
       }
       $this->x10_processo = ($this->x10_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_processo"]:$this->x10_processo);
     }else{
       $this->x10_codisencao = ($this->x10_codisencao == ""?@$GLOBALS["HTTP_POST_VARS"]["x10_codisencao"]:$this->x10_codisencao);
     }
   }
   // funcao para inclusao
   function incluir ($x10_codisencao){ 
      $this->atualizacampos();
     if($this->x10_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "x10_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x10_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "x10_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x10_codisencaotipo == null ){ 
       $this->erro_sql = " Campo Tipo se Isencao nao Informado.";
       $this->erro_campo = "x10_codisencaotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x10_dtini == null ){ 
       $this->erro_sql = " Campo Inicio nao Informado.";
       $this->erro_campo = "x10_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x10_dtfim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "x10_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x10_processo == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "x10_processo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x10_codisencao == "" || $x10_codisencao == null ){
       $result = db_query("select nextval('aguaisencao_x10_codisencao_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguaisencao_x10_codisencao_seq do campo: x10_codisencao"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x10_codisencao = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguaisencao_x10_codisencao_seq");
       if(($result != false) && (pg_result($result,0,0) < $x10_codisencao)){
         $this->erro_sql = " Campo x10_codisencao maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x10_codisencao = $x10_codisencao; 
       }
     }
     if(($this->x10_codisencao == null) || ($this->x10_codisencao == "") ){ 
       $this->erro_sql = " Campo x10_codisencao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaisencao(
                                       x10_matric 
                                      ,x10_obs 
                                      ,x10_codisencao 
                                      ,x10_codisencaotipo 
                                      ,x10_dtini 
                                      ,x10_dtfim 
                                      ,x10_processo 
                       )
                values (
                                $this->x10_matric 
                               ,'$this->x10_obs' 
                               ,$this->x10_codisencao 
                               ,$this->x10_codisencaotipo 
                               ,".($this->x10_dtini == "null" || $this->x10_dtini == ""?"null":"'".$this->x10_dtini."'")." 
                               ,".($this->x10_dtfim == "null" || $this->x10_dtfim == ""?"null":"'".$this->x10_dtfim."'")." 
                               ,$this->x10_processo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguaisencao ($this->x10_codisencao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguaisencao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguaisencao ($this->x10_codisencao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x10_codisencao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x10_codisencao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8445,'$this->x10_codisencao','I')");
       $resac = db_query("insert into db_acount values($acount,1424,8418,'','".AddSlashes(pg_result($resaco,0,'x10_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1424,8419,'','".AddSlashes(pg_result($resaco,0,'x10_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1424,8445,'','".AddSlashes(pg_result($resaco,0,'x10_codisencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1424,8449,'','".AddSlashes(pg_result($resaco,0,'x10_codisencaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1424,8450,'','".AddSlashes(pg_result($resaco,0,'x10_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1424,8451,'','".AddSlashes(pg_result($resaco,0,'x10_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1424,8452,'','".AddSlashes(pg_result($resaco,0,'x10_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x10_codisencao=null) { 
      $this->atualizacampos();
     $sql = " update aguaisencao set ";
     $virgula = "";
     if(trim($this->x10_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x10_matric"])){ 
       $sql  .= $virgula." x10_matric = $this->x10_matric ";
       $virgula = ",";
       if(trim($this->x10_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x10_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x10_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x10_obs"])){ 
       $sql  .= $virgula." x10_obs = '$this->x10_obs' ";
       $virgula = ",";
       if(trim($this->x10_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "x10_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x10_codisencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x10_codisencao"])){ 
       $sql  .= $virgula." x10_codisencao = $this->x10_codisencao ";
       $virgula = ",";
       if(trim($this->x10_codisencao) == null ){ 
         $this->erro_sql = " Campo Codigo Isencao nao Informado.";
         $this->erro_campo = "x10_codisencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x10_codisencaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x10_codisencaotipo"])){ 
       $sql  .= $virgula." x10_codisencaotipo = $this->x10_codisencaotipo ";
       $virgula = ",";
       if(trim($this->x10_codisencaotipo) == null ){ 
         $this->erro_sql = " Campo Tipo se Isencao nao Informado.";
         $this->erro_campo = "x10_codisencaotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x10_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x10_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x10_dtini_dia"] !="") ){ 
       $sql  .= $virgula." x10_dtini = '$this->x10_dtini' ";
       $virgula = ",";
       if(trim($this->x10_dtini) == null ){ 
         $this->erro_sql = " Campo Inicio nao Informado.";
         $this->erro_campo = "x10_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x10_dtini_dia"])){ 
         $sql  .= $virgula." x10_dtini = null ";
         $virgula = ",";
         if(trim($this->x10_dtini) == null ){ 
           $this->erro_sql = " Campo Inicio nao Informado.";
           $this->erro_campo = "x10_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x10_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x10_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x10_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." x10_dtfim = '$this->x10_dtfim' ";
       $virgula = ",";
       if(trim($this->x10_dtfim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "x10_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x10_dtfim_dia"])){ 
         $sql  .= $virgula." x10_dtfim = null ";
         $virgula = ",";
         if(trim($this->x10_dtfim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "x10_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x10_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x10_processo"])){ 
       $sql  .= $virgula." x10_processo = $this->x10_processo ";
       $virgula = ",";
       if(trim($this->x10_processo) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "x10_processo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x10_codisencao!=null){
       $sql .= " x10_codisencao = $this->x10_codisencao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x10_codisencao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8445,'$this->x10_codisencao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x10_matric"]))
           $resac = db_query("insert into db_acount values($acount,1424,8418,'".AddSlashes(pg_result($resaco,$conresaco,'x10_matric'))."','$this->x10_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x10_obs"]))
           $resac = db_query("insert into db_acount values($acount,1424,8419,'".AddSlashes(pg_result($resaco,$conresaco,'x10_obs'))."','$this->x10_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x10_codisencao"]))
           $resac = db_query("insert into db_acount values($acount,1424,8445,'".AddSlashes(pg_result($resaco,$conresaco,'x10_codisencao'))."','$this->x10_codisencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x10_codisencaotipo"]))
           $resac = db_query("insert into db_acount values($acount,1424,8449,'".AddSlashes(pg_result($resaco,$conresaco,'x10_codisencaotipo'))."','$this->x10_codisencaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x10_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1424,8450,'".AddSlashes(pg_result($resaco,$conresaco,'x10_dtini'))."','$this->x10_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x10_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1424,8451,'".AddSlashes(pg_result($resaco,$conresaco,'x10_dtfim'))."','$this->x10_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x10_processo"]))
           $resac = db_query("insert into db_acount values($acount,1424,8452,'".AddSlashes(pg_result($resaco,$conresaco,'x10_processo'))."','$this->x10_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaisencao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x10_codisencao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaisencao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x10_codisencao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x10_codisencao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x10_codisencao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x10_codisencao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8445,'$x10_codisencao','E')");
         $resac = db_query("insert into db_acount values($acount,1424,8418,'','".AddSlashes(pg_result($resaco,$iresaco,'x10_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1424,8419,'','".AddSlashes(pg_result($resaco,$iresaco,'x10_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1424,8445,'','".AddSlashes(pg_result($resaco,$iresaco,'x10_codisencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1424,8449,'','".AddSlashes(pg_result($resaco,$iresaco,'x10_codisencaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1424,8450,'','".AddSlashes(pg_result($resaco,$iresaco,'x10_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1424,8451,'','".AddSlashes(pg_result($resaco,$iresaco,'x10_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1424,8452,'','".AddSlashes(pg_result($resaco,$iresaco,'x10_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguaisencao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x10_codisencao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x10_codisencao = $x10_codisencao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaisencao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x10_codisencao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaisencao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x10_codisencao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x10_codisencao;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguaisencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x10_codisencao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaisencao ";
     $sql .= "      left join aguabase  on  aguabase.x01_matric = aguaisencao.x10_matric";
     $sql .= "      left join aguaisencaotipo  on  aguaisencaotipo.x29_codisencaotipo = aguaisencao.x10_codisencaotipo";
     $sql .= "      left join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      left join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x10_codisencao!=null ){
         $sql2 .= " where aguaisencao.x10_codisencao = $x10_codisencao "; 
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
   function sql_query_file ( $x10_codisencao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaisencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($x10_codisencao!=null ){
         $sql2 .= " where aguaisencao.x10_codisencao = $x10_codisencao "; 
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