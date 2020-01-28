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
//CLASSE DA ENTIDADE saltesplan
class cl_saltesplan { 
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
   var $k13_conta = 0; 
   var $c01_anousu = 0; 
   var $c01_reduz = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k13_conta = int4 = Código Conta 
                 c01_anousu = int4 = Exercício 
                 c01_reduz = int4 = Código Reduzido 
                 ";
   //funcao construtor da classe 
   function cl_saltesplan() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("saltesplan"); 
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
       $this->k13_conta = ($this->k13_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_conta"]:$this->k13_conta);
       $this->c01_anousu = ($this->c01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_anousu"]:$this->c01_anousu);
       $this->c01_reduz = ($this->c01_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_reduz"]:$this->c01_reduz);
     }else{
       $this->k13_conta = ($this->k13_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_conta"]:$this->k13_conta);
       $this->c01_anousu = ($this->c01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_anousu"]:$this->c01_anousu);
       $this->c01_reduz = ($this->c01_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c01_reduz"]:$this->c01_reduz);
     }
   }
   // funcao para inclusao
   function incluir ($k13_conta,$c01_anousu,$c01_reduz){ 
      $this->atualizacampos();
       $this->k13_conta = $k13_conta; 
       $this->c01_anousu = $c01_anousu; 
       $this->c01_reduz = $c01_reduz; 
     if(($this->k13_conta == null) || ($this->k13_conta == "") ){ 
       $this->erro_sql = " Campo k13_conta nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c01_anousu == null) || ($this->c01_anousu == "") ){ 
       $this->erro_sql = " Campo c01_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c01_reduz == null) || ($this->c01_reduz == "") ){ 
       $this->erro_sql = " Campo c01_reduz nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into saltesplan(
                                       k13_conta 
                                      ,c01_anousu 
                                      ,c01_reduz 
                       )
                values (
                                $this->k13_conta 
                               ,$this->c01_anousu 
                               ,$this->c01_reduz 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saltes / Plano ($this->k13_conta."-".$this->c01_anousu."-".$this->c01_reduz) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saltes / Plano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saltes / Plano ($this->k13_conta."-".$this->c01_anousu."-".$this->c01_reduz) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta."-".$this->c01_anousu."-".$this->c01_reduz;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->k13_conta,$this->c01_anousu,$this->c01_reduz));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1173,'$this->k13_conta','I')");
       $resac = pg_query("insert into db_acountkey values($acount,1274,'$this->c01_anousu','I')");
       $resac = pg_query("insert into db_acountkey values($acount,1276,'$this->c01_reduz','I')");
       $resac = pg_query("insert into db_acount values($acount,319,1173,'','".AddSlashes(pg_result($resaco,0,'k13_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,319,1274,'','".AddSlashes(pg_result($resaco,0,'c01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,319,1276,'','".AddSlashes(pg_result($resaco,0,'c01_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k13_conta=null,$c01_anousu=null,$c01_reduz=null) { 
      $this->atualizacampos();
     $sql = " update saltesplan set ";
     $virgula = "";
     if(trim($this->k13_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_conta"])){ 
       $sql  .= $virgula." k13_conta = $this->k13_conta ";
       $virgula = ",";
       if(trim($this->k13_conta) == null ){ 
         $this->erro_sql = " Campo Código Conta nao Informado.";
         $this->erro_campo = "k13_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c01_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c01_anousu"])){ 
       $sql  .= $virgula." c01_anousu = $this->c01_anousu ";
       $virgula = ",";
       if(trim($this->c01_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c01_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c01_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c01_reduz"])){ 
       $sql  .= $virgula." c01_reduz = $this->c01_reduz ";
       $virgula = ",";
       if(trim($this->c01_reduz) == null ){ 
         $this->erro_sql = " Campo Código Reduzido nao Informado.";
         $this->erro_campo = "c01_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  k13_conta = $this->k13_conta
 and  c01_anousu = $this->c01_anousu
 and  c01_reduz = $this->c01_reduz
";
     $resaco = $this->sql_record($this->sql_query_file($this->k13_conta,$this->c01_anousu,$this->c01_reduz));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1173,'$this->k13_conta','A')");
       $resac = pg_query("insert into db_acountkey values($acount,1274,'$this->c01_anousu','A')");
       $resac = pg_query("insert into db_acountkey values($acount,1276,'$this->c01_reduz','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k13_conta"]))
         $resac = pg_query("insert into db_acount values($acount,319,1173,'".AddSlashes(pg_result($resaco,0,'k13_conta'))."','$this->k13_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_anousu"]))
         $resac = pg_query("insert into db_acount values($acount,319,1274,'".AddSlashes(pg_result($resaco,0,'c01_anousu'))."','$this->c01_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c01_reduz"]))
         $resac = pg_query("insert into db_acount values($acount,319,1276,'".AddSlashes(pg_result($resaco,0,'c01_reduz'))."','$this->c01_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saltes / Plano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta."-".$this->c01_anousu."-".$this->c01_reduz;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saltes / Plano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta."-".$this->c01_anousu."-".$this->c01_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta."-".$this->c01_anousu."-".$this->c01_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k13_conta=null,$c01_anousu=null,$c01_reduz=null) { 
     $resaco = $this->sql_record($this->sql_query_file($k13_conta,$c01_anousu,$c01_reduz));
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1173,'".pg_result($resaco,$iresaco,'k13_conta')."','E')");
         $resac = pg_query("insert into db_acountkey values($acount,1274,'".pg_result($resaco,$iresaco,'c01_anousu')."','E')");
         $resac = pg_query("insert into db_acountkey values($acount,1276,'".pg_result($resaco,$iresaco,'c01_reduz')."','E')");
         $resac = pg_query("insert into db_acount values($acount,319,1173,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,319,1274,'','".AddSlashes(pg_result($resaco,$iresaco,'c01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,319,1276,'','".AddSlashes(pg_result($resaco,$iresaco,'c01_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from saltesplan
                    where ";
     $sql2 = "";
      if($k13_conta != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k13_conta = $k13_conta ";
}
      if($c01_anousu != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " c01_anousu = $c01_anousu ";
}
      if($c01_reduz != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " c01_reduz = $c01_reduz ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saltes / Plano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k13_conta."-".$c01_anousu."-".$c01_reduz;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saltes / Plano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k13_conta."-".$c01_anousu."-".$c01_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k13_conta."-".$c01_anousu."-".$c01_reduz;
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
        $this->erro_sql   = "Record Vazio na Tabela:saltesplan";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k13_conta=null,$c01_anousu=null,$c01_reduz=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from saltesplan ";
     $sql .= "      inner join saltes  on  saltes.k13_conta = saltesplan.k13_conta";
     $sql .= "      inner join conplanoexe  on  conplanoexe.c62_anousu = saltesplan.c01_anousu and  conplanoexe.c62_reduz = saltesplan.c01_reduz";
     $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_reduz = conplanoexe.c62_reduz and c61_anousu=c62_anousu";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = conplanoexe.c62_codrec";
     $sql .= "      inner join orctiporec  as a on   a.o15_codigo = conplanoexe.c62_codrec";
     $sql2 = "";
     if($dbwhere==""){
       if($k13_conta!=null ){
         $sql2 .= " where saltesplan.k13_conta = $k13_conta "; 
       } 
       if($c01_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " saltesplan.c01_anousu = $c01_anousu "; 
       } 
       if($c01_reduz!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " saltesplan.c01_reduz = $c01_reduz "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
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
   function sql_query_file ( $k13_conta=null,$c01_anousu=null,$c01_reduz=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from saltesplan ";
     $sql2 = "";
     if($dbwhere==""){
       if($k13_conta!=null ){
         $sql2 .= " where saltesplan.k13_conta = $k13_conta "; 
       } 
       if($c01_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " saltesplan.c01_anousu = $c01_anousu "; 
       } 
       if($c01_reduz!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " saltesplan.c01_reduz = $c01_reduz "; 
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