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
//CLASSE DA ENTIDADE aguaconfvenc
class cl_aguaconfvenc { 
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
   var $x33_exerc = 0; 
   var $x33_parcela = 0; 
   var $x33_dtvenc_dia = null; 
   var $x33_dtvenc_mes = null; 
   var $x33_dtvenc_ano = null; 
   var $x33_dtvenc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x33_exerc = int4 = Exercício 
                 x33_parcela = int4 = Parcela 
                 x33_dtvenc = date = Vencimento 
                 ";
   //funcao construtor da classe 
   function cl_aguaconfvenc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaconfvenc"); 
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
       $this->x33_exerc = ($this->x33_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["x33_exerc"]:$this->x33_exerc);
       $this->x33_parcela = ($this->x33_parcela == ""?@$GLOBALS["HTTP_POST_VARS"]["x33_parcela"]:$this->x33_parcela);
       if($this->x33_dtvenc == ""){
         $this->x33_dtvenc_dia = ($this->x33_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x33_dtvenc_dia"]:$this->x33_dtvenc_dia);
         $this->x33_dtvenc_mes = ($this->x33_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x33_dtvenc_mes"]:$this->x33_dtvenc_mes);
         $this->x33_dtvenc_ano = ($this->x33_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x33_dtvenc_ano"]:$this->x33_dtvenc_ano);
         if($this->x33_dtvenc_dia != ""){
            $this->x33_dtvenc = $this->x33_dtvenc_ano."-".$this->x33_dtvenc_mes."-".$this->x33_dtvenc_dia;
         }
       }
     }else{
       $this->x33_exerc = ($this->x33_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["x33_exerc"]:$this->x33_exerc);
       $this->x33_parcela = ($this->x33_parcela == ""?@$GLOBALS["HTTP_POST_VARS"]["x33_parcela"]:$this->x33_parcela);
     }
   }
   // funcao para inclusao
   function incluir ($x33_exerc,$x33_parcela){ 
      $this->atualizacampos();
     if($this->x33_dtvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "x33_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->x33_exerc = $x33_exerc; 
       $this->x33_parcela = $x33_parcela; 
     if(($this->x33_exerc == null) || ($this->x33_exerc == "") ){ 
       $this->erro_sql = " Campo x33_exerc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->x33_parcela == null) || ($this->x33_parcela == "") ){ 
       $this->erro_sql = " Campo x33_parcela nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaconfvenc(
                                       x33_exerc 
                                      ,x33_parcela 
                                      ,x33_dtvenc 
                       )
                values (
                                $this->x33_exerc 
                               ,$this->x33_parcela 
                               ,".($this->x33_dtvenc == "null" || $this->x33_dtvenc == ""?"null":"'".$this->x33_dtvenc."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vencimentos ($this->x33_exerc."-".$this->x33_parcela) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vencimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vencimentos ($this->x33_exerc."-".$this->x33_parcela) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x33_exerc."-".$this->x33_parcela;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x33_exerc,$this->x33_parcela));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8453,'$this->x33_exerc','I')");
       $resac = db_query("insert into db_acountkey values($acount,8454,'$this->x33_parcela','I')");
       $resac = db_query("insert into db_acount values($acount,1436,8453,'','".AddSlashes(pg_result($resaco,0,'x33_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1436,8454,'','".AddSlashes(pg_result($resaco,0,'x33_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1436,8515,'','".AddSlashes(pg_result($resaco,0,'x33_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x33_exerc=null,$x33_parcela=null) { 
      $this->atualizacampos();
     $sql = " update aguaconfvenc set ";
     $virgula = "";
     if(trim($this->x33_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x33_exerc"])){ 
       $sql  .= $virgula." x33_exerc = $this->x33_exerc ";
       $virgula = ",";
       if(trim($this->x33_exerc) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "x33_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x33_parcela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x33_parcela"])){ 
       $sql  .= $virgula." x33_parcela = $this->x33_parcela ";
       $virgula = ",";
       if(trim($this->x33_parcela) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "x33_parcela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x33_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x33_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x33_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." x33_dtvenc = '$this->x33_dtvenc' ";
       $virgula = ",";
       if(trim($this->x33_dtvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "x33_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x33_dtvenc_dia"])){ 
         $sql  .= $virgula." x33_dtvenc = null ";
         $virgula = ",";
         if(trim($this->x33_dtvenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "x33_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($x33_exerc!=null){
       $sql .= " x33_exerc = $this->x33_exerc";
     }
     if($x33_parcela!=null){
       $sql .= " and  x33_parcela = $this->x33_parcela";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x33_exerc,$this->x33_parcela));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8453,'$this->x33_exerc','A')");
         $resac = db_query("insert into db_acountkey values($acount,8454,'$this->x33_parcela','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x33_exerc"]))
           $resac = db_query("insert into db_acount values($acount,1436,8453,'".AddSlashes(pg_result($resaco,$conresaco,'x33_exerc'))."','$this->x33_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x33_parcela"]))
           $resac = db_query("insert into db_acount values($acount,1436,8454,'".AddSlashes(pg_result($resaco,$conresaco,'x33_parcela'))."','$this->x33_parcela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x33_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,1436,8515,'".AddSlashes(pg_result($resaco,$conresaco,'x33_dtvenc'))."','$this->x33_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vencimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x33_exerc."-".$this->x33_parcela;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vencimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x33_exerc."-".$this->x33_parcela;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x33_exerc."-".$this->x33_parcela;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x33_exerc=null,$x33_parcela=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x33_exerc,$x33_parcela));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8453,'$x33_exerc','E')");
         $resac = db_query("insert into db_acountkey values($acount,8454,'$x33_parcela','E')");
         $resac = db_query("insert into db_acount values($acount,1436,8453,'','".AddSlashes(pg_result($resaco,$iresaco,'x33_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1436,8454,'','".AddSlashes(pg_result($resaco,$iresaco,'x33_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1436,8515,'','".AddSlashes(pg_result($resaco,$iresaco,'x33_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguaconfvenc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x33_exerc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x33_exerc = $x33_exerc ";
        }
        if($x33_parcela != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x33_parcela = $x33_parcela ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vencimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x33_exerc."-".$x33_parcela;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vencimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x33_exerc."-".$x33_parcela;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x33_exerc."-".$x33_parcela;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguaconfvenc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x33_exerc=null,$x33_parcela=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconfvenc ";
     $sql2 = "";
     if($dbwhere==""){
       if($x33_exerc!=null ){
         $sql2 .= " where aguaconfvenc.x33_exerc = $x33_exerc "; 
       } 
       if($x33_parcela!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguaconfvenc.x33_parcela = $x33_parcela "; 
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
   function sql_query_file ( $x33_exerc=null,$x33_parcela=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconfvenc ";
     $sql2 = "";
     if($dbwhere==""){
       if($x33_exerc!=null ){
         $sql2 .= " where aguaconfvenc.x33_exerc = $x33_exerc "; 
       } 
       if($x33_parcela!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguaconfvenc.x33_parcela = $x33_parcela "; 
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