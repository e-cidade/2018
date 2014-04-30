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
//CLASSE DA ENTIDADE issquant
class cl_issquant { 
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
   var $q30_anousu = 0; 
   var $q30_inscr = 0; 
   var $q30_quant = 0; 
   var $q30_mult = 0; 
   var $q30_area = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q30_anousu = int4 = ano 
                 q30_inscr = int4 = inscricao 
                 q30_quant = float8 = Empregados 
                 q30_mult = float8 = multiplicador 
                 q30_area = float8 = Area 
                 ";
   //funcao construtor da classe 
   function cl_issquant() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issquant"); 
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
       $this->q30_anousu = ($this->q30_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_anousu"]:$this->q30_anousu);
       $this->q30_inscr = ($this->q30_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_inscr"]:$this->q30_inscr);
       $this->q30_quant = ($this->q30_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_quant"]:$this->q30_quant);
       $this->q30_mult = ($this->q30_mult == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_mult"]:$this->q30_mult);
       $this->q30_area = ($this->q30_area == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_area"]:$this->q30_area);
     }else{
       $this->q30_anousu = ($this->q30_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_anousu"]:$this->q30_anousu);
       $this->q30_inscr = ($this->q30_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_inscr"]:$this->q30_inscr);
     }
   }
   // funcao para inclusao
   function incluir ($q30_anousu,$q30_inscr){ 
      $this->atualizacampos();
     if($this->q30_quant == null ){ 
       $this->erro_sql = " Campo Empregados nao Informado.";
       $this->erro_campo = "q30_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q30_mult == null ){ 
       $this->erro_sql = " Campo multiplicador nao Informado.";
       $this->erro_campo = "q30_mult";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q30_area == null ){ 
       $this->erro_sql = " Campo Area nao Informado.";
       $this->erro_campo = "q30_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->q30_anousu = $q30_anousu; 
       $this->q30_inscr = $q30_inscr; 
     if(($this->q30_anousu == null) || ($this->q30_anousu == "") ){ 
       $this->erro_sql = " Campo q30_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q30_inscr == null) || ($this->q30_inscr == "") ){ 
       $this->erro_sql = " Campo q30_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issquant(
                                       q30_anousu 
                                      ,q30_inscr 
                                      ,q30_quant 
                                      ,q30_mult 
                                      ,q30_area 
                       )
                values (
                                $this->q30_anousu 
                               ,$this->q30_inscr 
                               ,$this->q30_quant 
                               ,$this->q30_mult 
                               ,$this->q30_area 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q30_anousu."-".$this->q30_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q30_anousu."-".$this->q30_inscr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q30_anousu,$this->q30_inscr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,285,'$this->q30_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,286,'$this->q30_inscr','I')");
       $resac = db_query("insert into db_acount values($acount,47,285,'','".AddSlashes(pg_result($resaco,0,'q30_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,47,286,'','".AddSlashes(pg_result($resaco,0,'q30_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,47,287,'','".AddSlashes(pg_result($resaco,0,'q30_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,47,288,'','".AddSlashes(pg_result($resaco,0,'q30_mult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,47,7428,'','".AddSlashes(pg_result($resaco,0,'q30_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q30_anousu=null,$q30_inscr=null) { 
      $this->atualizacampos();
     $sql = " update issquant set ";
     $virgula = "";
     if(trim($this->q30_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_anousu"])){ 
       $sql  .= $virgula." q30_anousu = $this->q30_anousu ";
       $virgula = ",";
       if(trim($this->q30_anousu) == null ){ 
         $this->erro_sql = " Campo ano nao Informado.";
         $this->erro_campo = "q30_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_inscr"])){ 
       $sql  .= $virgula." q30_inscr = $this->q30_inscr ";
       $virgula = ",";
       if(trim($this->q30_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q30_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_quant"])){ 
       $sql  .= $virgula." q30_quant = $this->q30_quant ";
       $virgula = ",";
       if(trim($this->q30_quant) == null ){ 
         $this->erro_sql = " Campo Empregados nao Informado.";
         $this->erro_campo = "q30_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_mult)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_mult"])){ 
       $sql  .= $virgula." q30_mult = $this->q30_mult ";
       $virgula = ",";
       if(trim($this->q30_mult) == null ){ 
         $this->erro_sql = " Campo multiplicador nao Informado.";
         $this->erro_campo = "q30_mult";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_area"])){ 
       $sql  .= $virgula." q30_area = $this->q30_area ";
       $virgula = ",";
       if(trim($this->q30_area) == null ){ 
         $this->erro_sql = " Campo Area nao Informado.";
         $this->erro_campo = "q30_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q30_anousu!=null){
       $sql .= " q30_anousu = $this->q30_anousu";
     }
     if($q30_inscr!=null){
       $sql .= " and  q30_inscr = $this->q30_inscr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q30_anousu,$this->q30_inscr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,285,'$this->q30_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,286,'$this->q30_inscr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q30_anousu"]))
           $resac = db_query("insert into db_acount values($acount,47,285,'".AddSlashes(pg_result($resaco,$conresaco,'q30_anousu'))."','$this->q30_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q30_inscr"]))
           $resac = db_query("insert into db_acount values($acount,47,286,'".AddSlashes(pg_result($resaco,$conresaco,'q30_inscr'))."','$this->q30_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q30_quant"]))
           $resac = db_query("insert into db_acount values($acount,47,287,'".AddSlashes(pg_result($resaco,$conresaco,'q30_quant'))."','$this->q30_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q30_mult"]))
           $resac = db_query("insert into db_acount values($acount,47,288,'".AddSlashes(pg_result($resaco,$conresaco,'q30_mult'))."','$this->q30_mult',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q30_area"]))
           $resac = db_query("insert into db_acount values($acount,47,7428,'".AddSlashes(pg_result($resaco,$conresaco,'q30_area'))."','$this->q30_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q30_anousu=null,$q30_inscr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q30_anousu,$q30_inscr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,285,'$q30_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,286,'$q30_inscr','E')");
         $resac = db_query("insert into db_acount values($acount,47,285,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,286,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,287,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,288,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_mult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,7428,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issquant
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q30_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q30_anousu = $q30_anousu ";
        }
        if($q30_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q30_inscr = $q30_inscr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q30_anousu."-".$q30_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q30_anousu."-".$q30_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q30_anousu."-".$q30_inscr;
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
        $this->erro_sql   = "Record Vazio na Tabela:issquant";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q30_anousu=null,$q30_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issquant ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issquant.q30_inscr";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q30_anousu!=null ){
         $sql2 .= " where issquant.q30_anousu = $q30_anousu "; 
       } 
       if($q30_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issquant.q30_inscr = $q30_inscr "; 
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
   function sql_query_file ( $q30_anousu=null,$q30_inscr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issquant ";
     $sql2 = "";
     if($dbwhere==""){
       if($q30_anousu!=null ){
         $sql2 .= " where issquant.q30_anousu = $q30_anousu "; 
       } 
       if($q30_inscr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " issquant.q30_inscr = $q30_inscr "; 
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