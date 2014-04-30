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
//CLASSE DA ENTIDADE aguabasevenc
class cl_aguabasevenc { 
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
   var $x27_matric = 0; 
   var $x27_parcela = 0; 
   var $x27_dtvenc_dia = null; 
   var $x27_dtvenc_mes = null; 
   var $x27_dtvenc_ano = null; 
   var $x27_dtvenc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x27_matric = int4 = Matrícula 
                 x27_parcela = int4 = Parcela 
                 x27_dtvenc = date = Vencimento 
                 ";
   //funcao construtor da classe 
   function cl_aguabasevenc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguabasevenc"); 
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
       $this->x27_matric = ($this->x27_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x27_matric"]:$this->x27_matric);
       $this->x27_parcela = ($this->x27_parcela == ""?@$GLOBALS["HTTP_POST_VARS"]["x27_parcela"]:$this->x27_parcela);
       if($this->x27_dtvenc == ""){
         $this->x27_dtvenc_dia = ($this->x27_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x27_dtvenc_dia"]:$this->x27_dtvenc_dia);
         $this->x27_dtvenc_mes = ($this->x27_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x27_dtvenc_mes"]:$this->x27_dtvenc_mes);
         $this->x27_dtvenc_ano = ($this->x27_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x27_dtvenc_ano"]:$this->x27_dtvenc_ano);
         if($this->x27_dtvenc_dia != ""){
            $this->x27_dtvenc = $this->x27_dtvenc_ano."-".$this->x27_dtvenc_mes."-".$this->x27_dtvenc_dia;
         }
       }
     }else{
       $this->x27_matric = ($this->x27_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x27_matric"]:$this->x27_matric);
       $this->x27_parcela = ($this->x27_parcela == ""?@$GLOBALS["HTTP_POST_VARS"]["x27_parcela"]:$this->x27_parcela);
     }
   }
   // funcao para inclusao
   function incluir ($x27_matric,$x27_parcela){ 
      $this->atualizacampos();
     if($this->x27_dtvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "x27_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->x27_matric = $x27_matric; 
       $this->x27_parcela = $x27_parcela; 
     if(($this->x27_matric == null) || ($this->x27_matric == "") ){ 
       $this->erro_sql = " Campo x27_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->x27_parcela == null) || ($this->x27_parcela == "") ){ 
       $this->erro_sql = " Campo x27_parcela nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguabasevenc(
                                       x27_matric 
                                      ,x27_parcela 
                                      ,x27_dtvenc 
                       )
                values (
                                $this->x27_matric 
                               ,$this->x27_parcela 
                               ,".($this->x27_dtvenc == "null" || $this->x27_dtvenc == ""?"null":"'".$this->x27_dtvenc."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguabasevenc ($this->x27_matric."-".$this->x27_parcela) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguabasevenc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguabasevenc ($this->x27_matric."-".$this->x27_parcela) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x27_matric."-".$this->x27_parcela;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x27_matric,$this->x27_parcela));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8516,'$this->x27_matric','I')");
       $resac = db_query("insert into db_acountkey values($acount,8517,'$this->x27_parcela','I')");
       $resac = db_query("insert into db_acount values($acount,1447,8516,'','".AddSlashes(pg_result($resaco,0,'x27_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1447,8517,'','".AddSlashes(pg_result($resaco,0,'x27_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1447,8518,'','".AddSlashes(pg_result($resaco,0,'x27_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x27_matric=null,$x27_parcela=null) { 
      $this->atualizacampos();
     $sql = " update aguabasevenc set ";
     $virgula = "";
     if(trim($this->x27_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x27_matric"])){ 
       $sql  .= $virgula." x27_matric = $this->x27_matric ";
       $virgula = ",";
       if(trim($this->x27_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x27_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x27_parcela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x27_parcela"])){ 
       $sql  .= $virgula." x27_parcela = $this->x27_parcela ";
       $virgula = ",";
       if(trim($this->x27_parcela) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "x27_parcela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x27_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x27_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x27_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." x27_dtvenc = '$this->x27_dtvenc' ";
       $virgula = ",";
       if(trim($this->x27_dtvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "x27_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x27_dtvenc_dia"])){ 
         $sql  .= $virgula." x27_dtvenc = null ";
         $virgula = ",";
         if(trim($this->x27_dtvenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "x27_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($x27_matric!=null){
       $sql .= " x27_matric = $this->x27_matric";
     }
     if($x27_parcela!=null){
       $sql .= " and  x27_parcela = $this->x27_parcela";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x27_matric,$this->x27_parcela));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8516,'$this->x27_matric','A')");
         $resac = db_query("insert into db_acountkey values($acount,8517,'$this->x27_parcela','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x27_matric"]))
           $resac = db_query("insert into db_acount values($acount,1447,8516,'".AddSlashes(pg_result($resaco,$conresaco,'x27_matric'))."','$this->x27_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x27_parcela"]))
           $resac = db_query("insert into db_acount values($acount,1447,8517,'".AddSlashes(pg_result($resaco,$conresaco,'x27_parcela'))."','$this->x27_parcela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x27_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,1447,8518,'".AddSlashes(pg_result($resaco,$conresaco,'x27_dtvenc'))."','$this->x27_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguabasevenc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x27_matric."-".$this->x27_parcela;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguabasevenc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x27_matric."-".$this->x27_parcela;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x27_matric."-".$this->x27_parcela;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x27_matric=null,$x27_parcela=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x27_matric,$x27_parcela));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8516,'$x27_matric','E')");
         $resac = db_query("insert into db_acountkey values($acount,8517,'$x27_parcela','E')");
         $resac = db_query("insert into db_acount values($acount,1447,8516,'','".AddSlashes(pg_result($resaco,$iresaco,'x27_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1447,8517,'','".AddSlashes(pg_result($resaco,$iresaco,'x27_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1447,8518,'','".AddSlashes(pg_result($resaco,$iresaco,'x27_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguabasevenc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x27_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x27_matric = $x27_matric ";
        }
        if($x27_parcela != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x27_parcela = $x27_parcela ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguabasevenc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x27_matric."-".$x27_parcela;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguabasevenc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x27_matric."-".$x27_parcela;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x27_matric."-".$x27_parcela;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguabasevenc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x27_matric=null,$x27_parcela=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguabasevenc ";
     $sql2 = "";
     if($dbwhere==""){
       if($x27_matric!=null ){
         $sql2 .= " where aguabasevenc.x27_matric = $x27_matric "; 
       } 
       if($x27_parcela!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguabasevenc.x27_parcela = $x27_parcela "; 
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
   function sql_query_file ( $x27_matric=null,$x27_parcela=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguabasevenc ";
     $sql2 = "";
     if($dbwhere==""){
       if($x27_matric!=null ){
         $sql2 .= " where aguabasevenc.x27_matric = $x27_matric "; 
       } 
       if($x27_parcela!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguabasevenc.x27_parcela = $x27_parcela "; 
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