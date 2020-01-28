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
//CLASSE DA ENTIDADE notidebitos
class cl_notidebitos { 
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
   var $k53_notifica = 0; 
   var $k53_numpre = 0; 
   var $k53_numpar = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k53_notifica = int4 = Notifica��o 
                 k53_numpre = int4 = Numpre 
                 k53_numpar = int4 = Parcela 
                 ";
   //funcao construtor da classe 
   function cl_notidebitos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notidebitos"); 
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
       $this->k53_notifica = ($this->k53_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k53_notifica"]:$this->k53_notifica);
       $this->k53_numpre = ($this->k53_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k53_numpre"]:$this->k53_numpre);
       $this->k53_numpar = ($this->k53_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k53_numpar"]:$this->k53_numpar);
     }else{
       $this->k53_notifica = ($this->k53_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k53_notifica"]:$this->k53_notifica);
       $this->k53_numpre = ($this->k53_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k53_numpre"]:$this->k53_numpre);
       $this->k53_numpar = ($this->k53_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k53_numpar"]:$this->k53_numpar);
     }
   }
   // funcao para inclusao
   function incluir ($k53_notifica,$k53_numpre,$k53_numpar){ 
      $this->atualizacampos();
       $this->k53_notifica = $k53_notifica; 
       $this->k53_numpre = $k53_numpre; 
       $this->k53_numpar = $k53_numpar; 
     if(($this->k53_notifica == null) || ($this->k53_notifica == "") ){ 
       $this->erro_sql = " Campo k53_notifica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k53_numpre == null) || ($this->k53_numpre == "") ){ 
       $this->erro_sql = " Campo k53_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k53_numpar == null) || ($this->k53_numpar == "") ){ 
       $this->erro_sql = " Campo k53_numpar nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notidebitos(
                                       k53_notifica 
                                      ,k53_numpre 
                                      ,k53_numpar 
                       )
                values (
                                $this->k53_notifica 
                               ,$this->k53_numpre 
                               ,$this->k53_numpar 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Notifica��o e Numpre ($this->k53_notifica."-".$this->k53_numpre."-".$this->k53_numpar) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Notifica��o e Numpre j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Notifica��o e Numpre ($this->k53_notifica."-".$this->k53_numpre."-".$this->k53_numpar) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k53_notifica."-".$this->k53_numpre."-".$this->k53_numpar;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k53_notifica,$this->k53_numpre,$this->k53_numpar));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4711,'$this->k53_notifica','I')");
       $resac = db_query("insert into db_acountkey values($acount,4712,'$this->k53_numpre','I')");
       $resac = db_query("insert into db_acountkey values($acount,4713,'$this->k53_numpar','I')");
       $resac = db_query("insert into db_acount values($acount,625,4711,'','".AddSlashes(pg_result($resaco,0,'k53_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,625,4712,'','".AddSlashes(pg_result($resaco,0,'k53_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,625,4713,'','".AddSlashes(pg_result($resaco,0,'k53_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k53_notifica=null,$k53_numpre=null,$k53_numpar=null) { 
      $this->atualizacampos();
     $sql = " update notidebitos set ";
     $virgula = "";
     if(trim($this->k53_notifica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k53_notifica"])){ 
        if(trim($this->k53_notifica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k53_notifica"])){ 
           $this->k53_notifica = "0" ; 
        } 
       $sql  .= $virgula." k53_notifica = $this->k53_notifica ";
       $virgula = ",";
       if(trim($this->k53_notifica) == null ){ 
         $this->erro_sql = " Campo Notifica��o nao Informado.";
         $this->erro_campo = "k53_notifica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k53_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k53_numpre"])){ 
        if(trim($this->k53_numpre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k53_numpre"])){ 
           $this->k53_numpre = "0" ; 
        } 
       $sql  .= $virgula." k53_numpre = $this->k53_numpre ";
       $virgula = ",";
       if(trim($this->k53_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k53_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k53_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k53_numpar"])){ 
        if(trim($this->k53_numpar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k53_numpar"])){ 
           $this->k53_numpar = "0" ; 
        } 
       $sql  .= $virgula." k53_numpar = $this->k53_numpar ";
       $virgula = ",";
       if(trim($this->k53_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k53_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k53_notifica!=null){
       $sql .= " k53_notifica = $this->k53_notifica";
     }
     if($k53_numpre!=null){
       $sql .= " and  k53_numpre = $this->k53_numpre";
     }
     if($k53_numpar!=null){
       $sql .= " and  k53_numpar = $this->k53_numpar";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k53_notifica,$this->k53_numpre,$this->k53_numpar));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4711,'$this->k53_notifica','A')");
         $resac = db_query("insert into db_acountkey values($acount,4712,'$this->k53_numpre','A')");
         $resac = db_query("insert into db_acountkey values($acount,4713,'$this->k53_numpar','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k53_notifica"]))
           $resac = db_query("insert into db_acount values($acount,625,4711,'".AddSlashes(pg_result($resaco,$conresaco,'k53_notifica'))."','$this->k53_notifica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k53_numpre"]))
           $resac = db_query("insert into db_acount values($acount,625,4712,'".AddSlashes(pg_result($resaco,$conresaco,'k53_numpre'))."','$this->k53_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k53_numpar"]))
           $resac = db_query("insert into db_acount values($acount,625,4713,'".AddSlashes(pg_result($resaco,$conresaco,'k53_numpar'))."','$this->k53_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notifica��o e Numpre nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k53_notifica."-".$this->k53_numpre."-".$this->k53_numpar;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notifica��o e Numpre nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k53_notifica."-".$this->k53_numpre."-".$this->k53_numpar;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k53_notifica."-".$this->k53_numpre."-".$this->k53_numpar;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k53_notifica=null,$k53_numpre=null,$k53_numpar=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k53_notifica,$k53_numpre,$k53_numpar));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4711,'$k53_notifica','E')");
         $resac = db_query("insert into db_acountkey values($acount,4712,'$k53_numpre','E')");
         $resac = db_query("insert into db_acountkey values($acount,4713,'$k53_numpar','E')");
         $resac = db_query("insert into db_acount values($acount,625,4711,'','".AddSlashes(pg_result($resaco,$iresaco,'k53_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,625,4712,'','".AddSlashes(pg_result($resaco,$iresaco,'k53_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,625,4713,'','".AddSlashes(pg_result($resaco,$iresaco,'k53_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notidebitos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k53_notifica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k53_notifica = $k53_notifica ";
        }
        if($k53_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k53_numpre = $k53_numpre ";
        }
        if($k53_numpar != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k53_numpar = $k53_numpar ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notifica��o e Numpre nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k53_notifica."-".$k53_numpre."-".$k53_numpar;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notifica��o e Numpre nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k53_notifica."-".$k53_numpre."-".$k53_numpar;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k53_notifica."-".$k53_numpre."-".$k53_numpar;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:notidebitos";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k53_notifica=null,$k53_numpre=null,$k53_numpar=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notidebitos ";
     $sql .= "      inner join notificacao  on  notificacao.k50_notifica = notidebitos.k53_notifica";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql2 = "";
     if($dbwhere==""){
       if($k53_notifica!=null ){
         $sql2 .= " where notidebitos.k53_notifica = $k53_notifica "; 
       } 
       if($k53_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " notidebitos.k53_numpre = $k53_numpre "; 
       } 
       if($k53_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " notidebitos.k53_numpar = $k53_numpar "; 
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
   function sql_query_file ( $k53_notifica=null,$k53_numpre=null,$k53_numpar=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notidebitos ";
     $sql2 = "";
     if($dbwhere==""){
       if($k53_notifica!=null ){
         $sql2 .= " where notidebitos.k53_notifica = $k53_notifica "; 
       } 
       if($k53_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " notidebitos.k53_numpre = $k53_numpre "; 
       } 
       if($k53_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " notidebitos.k53_numpar = $k53_numpar "; 
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