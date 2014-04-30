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

//MODULO: caixa
//CLASSE DA ENTIDADE notimatric
class cl_notimatric { 
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
   var $k55_notifica = 0; 
   var $k55_matric = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k55_notifica = int4 = Notificação 
                 k55_matric = int4 = Matrícula Imóvel 
                 ";
   //funcao construtor da classe 
   function cl_notimatric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notimatric"); 
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
       $this->k55_notifica = ($this->k55_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k55_notifica"]:$this->k55_notifica);
       $this->k55_matric = ($this->k55_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["k55_matric"]:$this->k55_matric);
     }else{
       $this->k55_notifica = ($this->k55_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k55_notifica"]:$this->k55_notifica);
       $this->k55_matric = ($this->k55_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["k55_matric"]:$this->k55_matric);
     }
   }
   // funcao para inclusao
   function incluir ($k55_notifica,$k55_matric){ 
      $this->atualizacampos();
       $this->k55_notifica = $k55_notifica; 
       $this->k55_matric = $k55_matric; 
     if(($this->k55_notifica == null) || ($this->k55_notifica == "") ){ 
       $this->erro_sql = " Campo k55_notifica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k55_matric == null) || ($this->k55_matric == "") ){ 
       $this->erro_sql = " Campo k55_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notimatric(
                                       k55_notifica 
                                      ,k55_matric 
                       )
                values (
                                $this->k55_notifica 
                               ,$this->k55_matric 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Notificacao com as matriculas ($this->k55_notifica."-".$this->k55_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Notificacao com as matriculas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Notificacao com as matriculas ($this->k55_notifica."-".$this->k55_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k55_notifica."-".$this->k55_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k55_notifica,$this->k55_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4719,'$this->k55_notifica','I')");
       $resac = db_query("insert into db_acountkey values($acount,4722,'$this->k55_matric','I')");
       $resac = db_query("insert into db_acount values($acount,626,4719,'','".AddSlashes(pg_result($resaco,0,'k55_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,626,4722,'','".AddSlashes(pg_result($resaco,0,'k55_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k55_notifica=null,$k55_matric=null) { 
      $this->atualizacampos();
     $sql = " update notimatric set ";
     $virgula = "";
     if(trim($this->k55_notifica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k55_notifica"])){ 
        if(trim($this->k55_notifica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k55_notifica"])){ 
           $this->k55_notifica = "0" ; 
        } 
       $sql  .= $virgula." k55_notifica = $this->k55_notifica ";
       $virgula = ",";
       if(trim($this->k55_notifica) == null ){ 
         $this->erro_sql = " Campo Notificação nao Informado.";
         $this->erro_campo = "k55_notifica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k55_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k55_matric"])){ 
        if(trim($this->k55_matric)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k55_matric"])){ 
           $this->k55_matric = "0" ; 
        } 
       $sql  .= $virgula." k55_matric = $this->k55_matric ";
       $virgula = ",";
       if(trim($this->k55_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula Imóvel nao Informado.";
         $this->erro_campo = "k55_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k55_notifica!=null){
       $sql .= " k55_notifica = $this->k55_notifica";
     }
     if($k55_matric!=null){
       $sql .= " and  k55_matric = $this->k55_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k55_notifica,$this->k55_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4719,'$this->k55_notifica','A')");
         $resac = db_query("insert into db_acountkey values($acount,4722,'$this->k55_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k55_notifica"]))
           $resac = db_query("insert into db_acount values($acount,626,4719,'".AddSlashes(pg_result($resaco,$conresaco,'k55_notifica'))."','$this->k55_notifica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k55_matric"]))
           $resac = db_query("insert into db_acount values($acount,626,4722,'".AddSlashes(pg_result($resaco,$conresaco,'k55_matric'))."','$this->k55_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notificacao com as matriculas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k55_notifica."-".$this->k55_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notificacao com as matriculas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k55_notifica."-".$this->k55_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k55_notifica."-".$this->k55_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k55_notifica=null,$k55_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k55_notifica,$k55_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4719,'$k55_notifica','E')");
         $resac = db_query("insert into db_acountkey values($acount,4722,'$k55_matric','E')");
         $resac = db_query("insert into db_acount values($acount,626,4719,'','".AddSlashes(pg_result($resaco,$iresaco,'k55_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,626,4722,'','".AddSlashes(pg_result($resaco,$iresaco,'k55_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notimatric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k55_notifica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k55_notifica = $k55_notifica ";
        }
        if($k55_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k55_matric = $k55_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notificacao com as matriculas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k55_notifica."-".$k55_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notificacao com as matriculas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k55_notifica."-".$k55_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k55_notifica."-".$k55_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:notimatric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k55_notifica=null,$k55_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notimatric ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = notimatric.k55_matric";
     $sql .= "      inner join notificacao  on  notificacao.k50_notifica = notimatric.k55_notifica";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql2 = "";
     if($dbwhere==""){
       if($k55_notifica!=null ){
         $sql2 .= " where notimatric.k55_notifica = $k55_notifica "; 
       } 
       if($k55_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " notimatric.k55_matric = $k55_matric "; 
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
   function sql_query_arrecad ( $k55_notifica=null,$k55_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notimatric 																		   ";
     $sql .= "	    inner join notidebitos    on notidebitos.k53_notifica    = notimatric.k55_notifica ";
     $sql .= "		inner join arrecad 	      on arrecad.k00_numpre          = notidebitos.k53_numpre  ";
     $sql .= "							     and arrecad.k00_numpar	   	     = notidebitos.k53_numpar  ";
     $sql .= "	    left  join notidebitosreg on notidebitosreg.k43_notifica = notimatric.k55_notifica ";     
     
     
     $sql2 = "";
     if($dbwhere==""){
       if($k55_notifica!=null ){
         $sql2 .= " where notimatric.k55_notifica = $k55_notifica "; 
       } 
       if($k55_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " notimatric.k55_matric = $k55_matric "; 
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
   function sql_query_file ( $k55_notifica=null,$k55_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notimatric ";
     $sql2 = "";
     if($dbwhere==""){
       if($k55_notifica!=null ){
         $sql2 .= " where notimatric.k55_notifica = $k55_notifica "; 
       } 
       if($k55_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " notimatric.k55_matric = $k55_matric "; 
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