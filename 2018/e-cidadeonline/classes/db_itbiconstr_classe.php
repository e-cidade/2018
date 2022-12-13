<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: ITBI
//CLASSE DA ENTIDADE itbiconstr
class cl_itbiconstr { 
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
   var $it08_codigo = 0; 
   var $it08_guia = 0; 
   var $it08_area = 0; 
   var $it08_areatrans = 0; 
   var $it08_ano = 0; 
   var $it08_obs = null; 
   var $it08_coordenadas = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it08_codigo = int8 = Código 
                 it08_guia = int8 = Número da guia de ITBI 
                 it08_area = float8 = Área 
                 it08_areatrans = float8 = Área Trans. 
                 it08_ano = int4 = Ano da construção 
                 it08_obs = varchar(50) = Observações 
                 it08_coordenadas = varchar(50) = Longitude/Latitude 
                 ";
   //funcao construtor da classe 
   function cl_itbiconstr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbiconstr"); 
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
       $this->it08_codigo = ($this->it08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_codigo"]:$this->it08_codigo);
       $this->it08_guia = ($this->it08_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_guia"]:$this->it08_guia);
       $this->it08_area = ($this->it08_area == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_area"]:$this->it08_area);
       $this->it08_areatrans = ($this->it08_areatrans == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_areatrans"]:$this->it08_areatrans);
       $this->it08_ano = ($this->it08_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_ano"]:$this->it08_ano);
       $this->it08_obs = ($this->it08_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_obs"]:$this->it08_obs);
       $this->it08_coordenadas = ($this->it08_coordenadas == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_coordenadas"]:$this->it08_coordenadas);
     }else{
       $this->it08_codigo = ($this->it08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["it08_codigo"]:$this->it08_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($it08_codigo){ 
      $this->atualizacampos();
     if($this->it08_guia == null ){ 
       $this->erro_sql = " Campo Número da guia de ITBI nao Informado.";
       $this->erro_campo = "it08_guia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it08_area == null ){ 
       $this->erro_sql = " Campo Área nao Informado.";
       $this->erro_campo = "it08_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it08_areatrans == null ){ 
       $this->erro_sql = " Campo Área Trans. nao Informado.";
       $this->erro_campo = "it08_areatrans";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it08_ano == null ){ 
       $this->erro_sql = " Campo Ano da construção nao Informado.";
       $this->erro_campo = "it08_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($it08_codigo == "" || $it08_codigo == null ){
       $result = db_query("select nextval('itbiconstr_it08_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbiconstr_it08_codigo_seq do campo: it08_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it08_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbiconstr_it08_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $it08_codigo)){
         $this->erro_sql = " Campo it08_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it08_codigo = $it08_codigo; 
       }
     }
     if(($this->it08_codigo == null) || ($this->it08_codigo == "") ){ 
       $this->erro_sql = " Campo it08_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbiconstr(
                                       it08_codigo 
                                      ,it08_guia 
                                      ,it08_area 
                                      ,it08_areatrans 
                                      ,it08_ano 
                                      ,it08_obs 
                                      ,it08_coordenadas 
                       )
                values (
                                $this->it08_codigo 
                               ,$this->it08_guia 
                               ,$this->it08_area 
                               ,$this->it08_areatrans 
                               ,$this->it08_ano 
                               ,'$this->it08_obs' 
                               ,'$this->it08_coordenadas' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Construções da ITBI ($this->it08_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Construções da ITBI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Construções da ITBI ($this->it08_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it08_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it08_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5417,'$this->it08_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,799,5417,'','".AddSlashes(pg_result($resaco,0,'it08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,799,5416,'','".AddSlashes(pg_result($resaco,0,'it08_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,799,5418,'','".AddSlashes(pg_result($resaco,0,'it08_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,799,5761,'','".AddSlashes(pg_result($resaco,0,'it08_areatrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,799,5419,'','".AddSlashes(pg_result($resaco,0,'it08_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,799,5424,'','".AddSlashes(pg_result($resaco,0,'it08_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,799,15482,'','".AddSlashes(pg_result($resaco,0,'it08_coordenadas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it08_codigo=null) { 
      $this->atualizacampos();
     $sql = " update itbiconstr set ";
     $virgula = "";
     if(trim($this->it08_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it08_codigo"])){ 
       $sql  .= $virgula." it08_codigo = $this->it08_codigo ";
       $virgula = ",";
       if(trim($this->it08_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "it08_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it08_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it08_guia"])){ 
       $sql  .= $virgula." it08_guia = $this->it08_guia ";
       $virgula = ",";
       if(trim($this->it08_guia) == null ){ 
         $this->erro_sql = " Campo Número da guia de ITBI nao Informado.";
         $this->erro_campo = "it08_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it08_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it08_area"])){ 
       $sql  .= $virgula." it08_area = $this->it08_area ";
       $virgula = ",";
       if(trim($this->it08_area) == null ){ 
         $this->erro_sql = " Campo Área nao Informado.";
         $this->erro_campo = "it08_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it08_areatrans)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it08_areatrans"])){ 
       $sql  .= $virgula." it08_areatrans = $this->it08_areatrans ";
       $virgula = ",";
       if(trim($this->it08_areatrans) == null ){ 
         $this->erro_sql = " Campo Área Trans. nao Informado.";
         $this->erro_campo = "it08_areatrans";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it08_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it08_ano"])){ 
       $sql  .= $virgula." it08_ano = $this->it08_ano ";
       $virgula = ",";
       if(trim($this->it08_ano) == null ){ 
         $this->erro_sql = " Campo Ano da construção nao Informado.";
         $this->erro_campo = "it08_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it08_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it08_obs"])){ 
       $sql  .= $virgula." it08_obs = '$this->it08_obs' ";
       $virgula = ",";
     }
     if(trim($this->it08_coordenadas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it08_coordenadas"])){ 
       $sql  .= $virgula." it08_coordenadas = '$this->it08_coordenadas' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($it08_codigo!=null){
       $sql .= " it08_codigo = $this->it08_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it08_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5417,'$this->it08_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it08_codigo"]) || $this->it08_codigo != "")
           $resac = db_query("insert into db_acount values($acount,799,5417,'".AddSlashes(pg_result($resaco,$conresaco,'it08_codigo'))."','$this->it08_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it08_guia"]) || $this->it08_guia != "")
           $resac = db_query("insert into db_acount values($acount,799,5416,'".AddSlashes(pg_result($resaco,$conresaco,'it08_guia'))."','$this->it08_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it08_area"]) || $this->it08_area != "")
           $resac = db_query("insert into db_acount values($acount,799,5418,'".AddSlashes(pg_result($resaco,$conresaco,'it08_area'))."','$this->it08_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it08_areatrans"]) || $this->it08_areatrans != "")
           $resac = db_query("insert into db_acount values($acount,799,5761,'".AddSlashes(pg_result($resaco,$conresaco,'it08_areatrans'))."','$this->it08_areatrans',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it08_ano"]) || $this->it08_ano != "")
           $resac = db_query("insert into db_acount values($acount,799,5419,'".AddSlashes(pg_result($resaco,$conresaco,'it08_ano'))."','$this->it08_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it08_obs"]) || $this->it08_obs != "")
           $resac = db_query("insert into db_acount values($acount,799,5424,'".AddSlashes(pg_result($resaco,$conresaco,'it08_obs'))."','$this->it08_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it08_coordenadas"]) || $this->it08_coordenadas != "")
           $resac = db_query("insert into db_acount values($acount,799,15482,'".AddSlashes(pg_result($resaco,$conresaco,'it08_coordenadas'))."','$this->it08_coordenadas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Construções da ITBI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Construções da ITBI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it08_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it08_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5417,'$it08_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,799,5417,'','".AddSlashes(pg_result($resaco,$iresaco,'it08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,799,5416,'','".AddSlashes(pg_result($resaco,$iresaco,'it08_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,799,5418,'','".AddSlashes(pg_result($resaco,$iresaco,'it08_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,799,5761,'','".AddSlashes(pg_result($resaco,$iresaco,'it08_areatrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,799,5419,'','".AddSlashes(pg_result($resaco,$iresaco,'it08_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,799,5424,'','".AddSlashes(pg_result($resaco,$iresaco,'it08_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,799,15482,'','".AddSlashes(pg_result($resaco,$iresaco,'it08_coordenadas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbiconstr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it08_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it08_codigo = $it08_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Construções da ITBI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Construções da ITBI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it08_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbiconstr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it08_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbiconstr ";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbiconstr.it08_guia";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if($dbwhere==""){
       if($it08_codigo!=null ){
         $sql2 .= " where itbiconstr.it08_codigo = $it08_codigo "; 
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
   function sql_query_file ( $it08_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbiconstr ";
     $sql2 = "";
     if($dbwhere==""){
       if($it08_codigo!=null ){
         $sql2 .= " where itbiconstr.it08_codigo = $it08_codigo "; 
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

   function sql_query_espec( $it08_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbiconstr ";
     $sql .= "      inner join itbiconstrespecie  on  itbiconstrespecie.it09_codigo = itbiconstr.it08_codigo";
     $sql .= "      inner join caracter 		  on  caracter.j31_codigo		    = itbiconstrespecie.it09_caract";
     
     $sql2 = "";
     if($dbwhere==""){
       if($it08_codigo!=null ){
         $sql2 .= " where itbiconstr.it08_codigo = $it08_codigo "; 
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