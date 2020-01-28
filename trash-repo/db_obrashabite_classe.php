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

//MODULO: projetos
//CLASSE DA ENTIDADE obrashabite
class cl_obrashabite { 
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
   var $ob09_codhab = 0; 
   var $ob09_codconstr = 0; 
   var $ob09_habite = null; 
   var $ob09_parcial = 'f'; 
   var $ob09_data_dia = null; 
   var $ob09_data_mes = null; 
   var $ob09_data_ano = null; 
   var $ob09_data = null; 
   var $ob09_area = 0; 
   var $ob09_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob09_codhab = int4 = Código do habite-se 
                 ob09_codconstr = int4 = Código da construção 
                 ob09_habite = varchar(15) = Habite-se 
                 ob09_parcial = bool = Parcial 
                 ob09_data = date = Data do habite-se 
                 ob09_area = float8 = Área 
                 ob09_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_obrashabite() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrashabite"); 
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
       $this->ob09_codhab = ($this->ob09_codhab == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_codhab"]:$this->ob09_codhab);
       $this->ob09_codconstr = ($this->ob09_codconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_codconstr"]:$this->ob09_codconstr);
       $this->ob09_habite = ($this->ob09_habite == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_habite"]:$this->ob09_habite);
       $this->ob09_parcial = ($this->ob09_parcial == "f"?@$GLOBALS["HTTP_POST_VARS"]["ob09_parcial"]:$this->ob09_parcial);
       if($this->ob09_data == ""){
         $this->ob09_data_dia = ($this->ob09_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_data_dia"]:$this->ob09_data_dia);
         $this->ob09_data_mes = ($this->ob09_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_data_mes"]:$this->ob09_data_mes);
         $this->ob09_data_ano = ($this->ob09_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_data_ano"]:$this->ob09_data_ano);
         if($this->ob09_data_dia != ""){
            $this->ob09_data = $this->ob09_data_ano."-".$this->ob09_data_mes."-".$this->ob09_data_dia;
         }
       }
       $this->ob09_area = ($this->ob09_area == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_area"]:$this->ob09_area);
       $this->ob09_obs = ($this->ob09_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_obs"]:$this->ob09_obs);
     }else{
       $this->ob09_codhab = ($this->ob09_codhab == ""?@$GLOBALS["HTTP_POST_VARS"]["ob09_codhab"]:$this->ob09_codhab);
     }
   }
   // funcao para inclusao
   function incluir ($ob09_codhab){ 
      $this->atualizacampos();
     if($this->ob09_codconstr == null ){ 
       $this->erro_sql = " Campo Código da construção nao Informado.";
       $this->erro_campo = "ob09_codconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob09_habite == null ){ 
       $this->erro_sql = " Campo Habite-se nao Informado.";
       $this->erro_campo = "ob09_habite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob09_parcial == null ){ 
       $this->erro_sql = " Campo Parcial nao Informado.";
       $this->erro_campo = "ob09_parcial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob09_data == null ){ 
       $this->erro_sql = " Campo Data do habite-se nao Informado.";
       $this->erro_campo = "ob09_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob09_area == null ){ 
       $this->erro_sql = " Campo Área nao Informado.";
       $this->erro_campo = "ob09_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob09_codhab == "" || $ob09_codhab == null ){
       $result = @pg_query("select nextval('obrashabite_ob09_codhab_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrashabite_ob09_codhab_seq do campo: ob09_codhab"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob09_codhab = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from obrashabite_ob09_codhab_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob09_codhab)){
         $this->erro_sql = " Campo ob09_codhab maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob09_codhab = $ob09_codhab; 
       }
     }
     if(($this->ob09_codhab == null) || ($this->ob09_codhab == "") ){ 
       $this->erro_sql = " Campo ob09_codhab nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrashabite(
                                       ob09_codhab 
                                      ,ob09_codconstr 
                                      ,ob09_habite 
                                      ,ob09_parcial 
                                      ,ob09_data 
                                      ,ob09_area 
                                      ,ob09_obs 
                       )
                values (
                                $this->ob09_codhab 
                               ,$this->ob09_codconstr 
                               ,'$this->ob09_habite' 
                               ,'$this->ob09_parcial' 
                               ,".($this->ob09_data == "null" || $this->ob09_data == ""?"null":"'".$this->ob09_data."'")." 
                               ,$this->ob09_area 
                               ,'$this->ob09_obs' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "habite-se da obra ($this->ob09_codhab) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "habite-se da obra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "habite-se da obra ($this->ob09_codhab) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob09_codhab;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->ob09_codhab));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5972,'$this->ob09_codhab','I')");
       $resac = pg_query("insert into db_acount values($acount,954,5972,'','".AddSlashes(pg_result($resaco,0,'ob09_codhab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,954,5973,'','".AddSlashes(pg_result($resaco,0,'ob09_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,954,5974,'','".AddSlashes(pg_result($resaco,0,'ob09_habite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,954,5975,'','".AddSlashes(pg_result($resaco,0,'ob09_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,954,5976,'','".AddSlashes(pg_result($resaco,0,'ob09_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,954,5977,'','".AddSlashes(pg_result($resaco,0,'ob09_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,954,5978,'','".AddSlashes(pg_result($resaco,0,'ob09_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob09_codhab=null) { 
      $this->atualizacampos();
     $sql = " update obrashabite set ";
     $virgula = "";
     if(trim($this->ob09_codhab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob09_codhab"])){ 
       $sql  .= $virgula." ob09_codhab = $this->ob09_codhab ";
       $virgula = ",";
       if(trim($this->ob09_codhab) == null ){ 
         $this->erro_sql = " Campo Código do habite-se nao Informado.";
         $this->erro_campo = "ob09_codhab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob09_codconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob09_codconstr"])){ 
       $sql  .= $virgula." ob09_codconstr = $this->ob09_codconstr ";
       $virgula = ",";
       if(trim($this->ob09_codconstr) == null ){ 
         $this->erro_sql = " Campo Código da construção nao Informado.";
         $this->erro_campo = "ob09_codconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob09_habite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob09_habite"])){ 
       $sql  .= $virgula." ob09_habite = '$this->ob09_habite' ";
       $virgula = ",";
       if(trim($this->ob09_habite) == null ){ 
         $this->erro_sql = " Campo Habite-se nao Informado.";
         $this->erro_campo = "ob09_habite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob09_parcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob09_parcial"])){ 
       $sql  .= $virgula." ob09_parcial = '$this->ob09_parcial' ";
       $virgula = ",";
       if(trim($this->ob09_parcial) == null ){ 
         $this->erro_sql = " Campo Parcial nao Informado.";
         $this->erro_campo = "ob09_parcial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob09_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob09_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob09_data_dia"] !="") ){ 
       $sql  .= $virgula." ob09_data = '$this->ob09_data' ";
       $virgula = ",";
       if(trim($this->ob09_data) == null ){ 
         $this->erro_sql = " Campo Data do habite-se nao Informado.";
         $this->erro_campo = "ob09_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_data_dia"])){ 
         $sql  .= $virgula." ob09_data = null ";
         $virgula = ",";
         if(trim($this->ob09_data) == null ){ 
           $this->erro_sql = " Campo Data do habite-se nao Informado.";
           $this->erro_campo = "ob09_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob09_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob09_area"])){ 
       $sql  .= $virgula." ob09_area = $this->ob09_area ";
       $virgula = ",";
       if(trim($this->ob09_area) == null ){ 
         $this->erro_sql = " Campo Área nao Informado.";
         $this->erro_campo = "ob09_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob09_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob09_obs"])){ 
       $sql  .= $virgula." ob09_obs = '$this->ob09_obs' ";
       $virgula = ",";
     }
     $sql .= " where  ob09_codhab = $this->ob09_codhab
";
     $resaco = $this->sql_record($this->sql_query_file($this->ob09_codhab));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5972,'$this->ob09_codhab','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_codhab"]))
         $resac = pg_query("insert into db_acount values($acount,954,5972,'".AddSlashes(pg_result($resaco,0,'ob09_codhab'))."','$this->ob09_codhab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_codconstr"]))
         $resac = pg_query("insert into db_acount values($acount,954,5973,'".AddSlashes(pg_result($resaco,0,'ob09_codconstr'))."','$this->ob09_codconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_habite"]))
         $resac = pg_query("insert into db_acount values($acount,954,5974,'".AddSlashes(pg_result($resaco,0,'ob09_habite'))."','$this->ob09_habite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_parcial"]))
         $resac = pg_query("insert into db_acount values($acount,954,5975,'".AddSlashes(pg_result($resaco,0,'ob09_parcial'))."','$this->ob09_parcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_data"]))
         $resac = pg_query("insert into db_acount values($acount,954,5976,'".AddSlashes(pg_result($resaco,0,'ob09_data'))."','$this->ob09_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_area"]))
         $resac = pg_query("insert into db_acount values($acount,954,5977,'".AddSlashes(pg_result($resaco,0,'ob09_area'))."','$this->ob09_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob09_obs"]))
         $resac = pg_query("insert into db_acount values($acount,954,5978,'".AddSlashes(pg_result($resaco,0,'ob09_obs'))."','$this->ob09_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "habite-se da obra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob09_codhab;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "habite-se da obra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob09_codhab;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob09_codhab;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob09_codhab=null) { 
     $resaco = $this->sql_record($this->sql_query_file($ob09_codhab));
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5972,'$this->ob09_codhab','E')");
         $resac = pg_query("insert into db_acount values($acount,954,5972,'','".AddSlashes(pg_result($resaco,$iresaco,'ob09_codhab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,954,5973,'','".AddSlashes(pg_result($resaco,$iresaco,'ob09_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,954,5974,'','".AddSlashes(pg_result($resaco,$iresaco,'ob09_habite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,954,5975,'','".AddSlashes(pg_result($resaco,$iresaco,'ob09_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,954,5976,'','".AddSlashes(pg_result($resaco,$iresaco,'ob09_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,954,5977,'','".AddSlashes(pg_result($resaco,$iresaco,'ob09_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,954,5978,'','".AddSlashes(pg_result($resaco,$iresaco,'ob09_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obrashabite
                    where ";
     $sql2 = "";
      if($ob09_codhab != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " ob09_codhab = $ob09_codhab ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "habite-se da obra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob09_codhab;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "habite-se da obra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob09_codhab;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob09_codhab;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrashabite";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ob09_codhab=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrashabite ";
     $sql .= "      inner join obrasconstr  on  obrasconstr.ob08_codconstr = obrashabite.ob09_codconstr";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasconstr.ob08_codobra";
     $sql .= "      inner join obraspropri  on obras.ob01_codobra = obraspropri.ob03_codobra";
     $sql .= "      inner join cgm  on cgm.z01_numcgm = obraspropri.ob03_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ob09_codhab!=null ){
         $sql2 .= " where obrashabite.ob09_codhab = $ob09_codhab "; 
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
   function sql_query_file ( $ob09_codhab=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrashabite ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob09_codhab!=null ){
         $sql2 .= " where obrashabite.ob09_codhab = $ob09_codhab "; 
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
   function sql_query_obco ( $ob09_codhab=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrashabite ";
     $sql .= "      inner join obrasconstr  on  obrasconstr.ob08_codconstr = obrashabite.ob09_codconstr";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasconstr.ob08_codobra";
     $sql .= "      inner join obraspropri  on obras.ob01_codobra = obraspropri.ob03_codobra";
     $sql .= "      inner join cgm  on cgm.z01_numcgm = obraspropri.ob03_numcgm";
     $sql .= "      inner join caracter a on a.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join caracter b on b.j31_codigo = obrasconstr.ob08_tipoconstr";
     $sql .= "      inner join caracter c on c.j31_codigo = obrasconstr.ob08_tipolanc";
     $sql2 = "";
     if($dbwhere==""){
       if($ob09_codhab!=null ){
         $sql2 .= " where obrashabite.ob09_codhab = $ob09_codhab "; 
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