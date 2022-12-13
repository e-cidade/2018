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

//MODULO: agua
//CLASSE DA ENTIDADE aguahidrotroca
class cl_aguahidrotroca { 
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
   var $x28_codigo = 0; 
   var $x28_dttroca_dia = null; 
   var $x28_dttroca_mes = null; 
   var $x28_dttroca_ano = null; 
   var $x28_dttroca = null; 
   var $x28_obs = null; 
   var $x28_codhidrometro = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x28_codigo = int4 = Caracteristica 
                 x28_dttroca = date = Data de Troca 
                 x28_obs = text = Observações 
                 x28_codhidrometro = int4 = Código do hidrômetro 
                 ";
   //funcao construtor da classe 
   function cl_aguahidrotroca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguahidrotroca"); 
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
       $this->x28_codigo = ($this->x28_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["x28_codigo"]:$this->x28_codigo);
       if($this->x28_dttroca == ""){
         $this->x28_dttroca_dia = ($this->x28_dttroca_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x28_dttroca_dia"]:$this->x28_dttroca_dia);
         $this->x28_dttroca_mes = ($this->x28_dttroca_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x28_dttroca_mes"]:$this->x28_dttroca_mes);
         $this->x28_dttroca_ano = ($this->x28_dttroca_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x28_dttroca_ano"]:$this->x28_dttroca_ano);
         if($this->x28_dttroca_dia != ""){
            $this->x28_dttroca = $this->x28_dttroca_ano."-".$this->x28_dttroca_mes."-".$this->x28_dttroca_dia;
         }
       }
       $this->x28_obs = ($this->x28_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["x28_obs"]:$this->x28_obs);
       $this->x28_codhidrometro = ($this->x28_codhidrometro == ""?@$GLOBALS["HTTP_POST_VARS"]["x28_codhidrometro"]:$this->x28_codhidrometro);
     }else{
       $this->x28_codhidrometro = ($this->x28_codhidrometro == ""?@$GLOBALS["HTTP_POST_VARS"]["x28_codhidrometro"]:$this->x28_codhidrometro);
     }
   }
   // funcao para inclusao
   function incluir ($x28_codhidrometro){ 
      $this->atualizacampos();
     if($this->x28_codigo == null ){ 
       $this->erro_sql = " Campo Caracteristica nao Informado.";
       $this->erro_campo = "x28_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x28_dttroca == null ){ 
       $this->erro_sql = " Campo Data de Troca nao Informado.";
       $this->erro_campo = "x28_dttroca_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x28_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "x28_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->x28_codhidrometro = $x28_codhidrometro; 
     if(($this->x28_codhidrometro == null) || ($this->x28_codhidrometro == "") ){ 
       $this->erro_sql = " Campo x28_codhidrometro nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguahidrotroca(
                                       x28_codigo 
                                      ,x28_dttroca 
                                      ,x28_obs 
                                      ,x28_codhidrometro 
                       )
                values (
                                $this->x28_codigo 
                               ,".($this->x28_dttroca == "null" || $this->x28_dttroca == ""?"null":"'".$this->x28_dttroca."'")." 
                               ,'$this->x28_obs' 
                               ,$this->x28_codhidrometro 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguahidrotroca ($this->x28_codhidrometro) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguahidrotroca já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguahidrotroca ($this->x28_codhidrometro) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x28_codhidrometro;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x28_codhidrometro));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8438,'$this->x28_codhidrometro','I')");
       $resac = db_query("insert into db_acount values($acount,1433,8435,'','".AddSlashes(pg_result($resaco,0,'x28_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1433,8436,'','".AddSlashes(pg_result($resaco,0,'x28_dttroca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1433,8437,'','".AddSlashes(pg_result($resaco,0,'x28_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1433,8438,'','".AddSlashes(pg_result($resaco,0,'x28_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x28_codhidrometro=null) { 
      $this->atualizacampos();
     $sql = " update aguahidrotroca set ";
     $virgula = "";
     if(trim($this->x28_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x28_codigo"])){ 
       $sql  .= $virgula." x28_codigo = $this->x28_codigo ";
       $virgula = ",";
       if(trim($this->x28_codigo) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "x28_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x28_dttroca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x28_dttroca_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x28_dttroca_dia"] !="") ){ 
       $sql  .= $virgula." x28_dttroca = '$this->x28_dttroca' ";
       $virgula = ",";
       if(trim($this->x28_dttroca) == null ){ 
         $this->erro_sql = " Campo Data de Troca nao Informado.";
         $this->erro_campo = "x28_dttroca_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x28_dttroca_dia"])){ 
         $sql  .= $virgula." x28_dttroca = null ";
         $virgula = ",";
         if(trim($this->x28_dttroca) == null ){ 
           $this->erro_sql = " Campo Data de Troca nao Informado.";
           $this->erro_campo = "x28_dttroca_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x28_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x28_obs"])){ 
       $sql  .= $virgula." x28_obs = '$this->x28_obs' ";
       $virgula = ",";
       if(trim($this->x28_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "x28_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x28_codhidrometro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x28_codhidrometro"])){ 
       $sql  .= $virgula." x28_codhidrometro = $this->x28_codhidrometro ";
       $virgula = ",";
       if(trim($this->x28_codhidrometro) == null ){ 
         $this->erro_sql = " Campo Código do hidrômetro nao Informado.";
         $this->erro_campo = "x28_codhidrometro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x28_codhidrometro!=null){
       $sql .= " x28_codhidrometro = $this->x28_codhidrometro";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x28_codhidrometro));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8438,'$this->x28_codhidrometro','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x28_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1433,8435,'".AddSlashes(pg_result($resaco,$conresaco,'x28_codigo'))."','$this->x28_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x28_dttroca"]))
           $resac = db_query("insert into db_acount values($acount,1433,8436,'".AddSlashes(pg_result($resaco,$conresaco,'x28_dttroca'))."','$this->x28_dttroca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x28_obs"]))
           $resac = db_query("insert into db_acount values($acount,1433,8437,'".AddSlashes(pg_result($resaco,$conresaco,'x28_obs'))."','$this->x28_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x28_codhidrometro"]))
           $resac = db_query("insert into db_acount values($acount,1433,8438,'".AddSlashes(pg_result($resaco,$conresaco,'x28_codhidrometro'))."','$this->x28_codhidrometro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguahidrotroca nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x28_codhidrometro;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguahidrotroca nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x28_codhidrometro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x28_codhidrometro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x28_codhidrometro=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x28_codhidrometro));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8438,'$x28_codhidrometro','E')");
         $resac = db_query("insert into db_acount values($acount,1433,8435,'','".AddSlashes(pg_result($resaco,$iresaco,'x28_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1433,8436,'','".AddSlashes(pg_result($resaco,$iresaco,'x28_dttroca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1433,8437,'','".AddSlashes(pg_result($resaco,$iresaco,'x28_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1433,8438,'','".AddSlashes(pg_result($resaco,$iresaco,'x28_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguahidrotroca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x28_codhidrometro != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x28_codhidrometro = $x28_codhidrometro ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguahidrotroca nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x28_codhidrometro;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguahidrotroca nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x28_codhidrometro;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x28_codhidrometro;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguahidrotroca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x28_codhidrometro=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguahidrotroca ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = aguahidrotroca.x28_codigo";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = aguahidrotroca.x28_codhidrometro";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguahidromatric.x04_matric";
     $sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
     $sql2 = "";
     if($dbwhere==""){
       if($x28_codhidrometro!=null ){
         $sql2 .= " where aguahidrotroca.x28_codhidrometro = $x28_codhidrometro "; 
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
   function sql_query_file ( $x28_codhidrometro=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguahidrotroca ";
     $sql2 = "";
     if($dbwhere==""){
       if($x28_codhidrometro!=null ){
         $sql2 .= " where aguahidrotroca.x28_codhidrometro = $x28_codhidrometro "; 
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