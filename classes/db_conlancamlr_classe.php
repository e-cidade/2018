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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamlr
class cl_conlancamlr { 
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
   var $c81_sequen = 0; 
   var $c81_seqtranslr = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c81_sequen = int4 = Código Lançamento 
                 c81_seqtranslr = int4 = Sequencia Translr 
                 ";
   //funcao construtor da classe 
   function cl_conlancamlr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamlr"); 
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
       $this->c81_sequen = ($this->c81_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["c81_sequen"]:$this->c81_sequen);
       $this->c81_seqtranslr = ($this->c81_seqtranslr == ""?@$GLOBALS["HTTP_POST_VARS"]["c81_seqtranslr"]:$this->c81_seqtranslr);
     }else{
       $this->c81_sequen = ($this->c81_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["c81_sequen"]:$this->c81_sequen);
       $this->c81_seqtranslr = ($this->c81_seqtranslr == ""?@$GLOBALS["HTTP_POST_VARS"]["c81_seqtranslr"]:$this->c81_seqtranslr);
     }
   }
   // funcao para inclusao
   function incluir ($c81_sequen,$c81_seqtranslr){ 
      $this->atualizacampos();
       $this->c81_sequen = $c81_sequen; 
       $this->c81_seqtranslr = $c81_seqtranslr; 
     if(($this->c81_sequen == null) || ($this->c81_sequen == "") ){ 
       $this->erro_sql = " Campo c81_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c81_seqtranslr == null) || ($this->c81_seqtranslr == "") ){ 
       $this->erro_sql = " Campo c81_seqtranslr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamlr(
                                       c81_sequen 
                                      ,c81_seqtranslr 
                       )
                values (
                                $this->c81_sequen 
                               ,$this->c81_seqtranslr 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamentos Automáticos - Previsão ($this->c81_sequen."-".$this->c81_seqtranslr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamentos Automáticos - Previsão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamentos Automáticos - Previsão ($this->c81_sequen."-".$this->c81_seqtranslr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c81_sequen."-".$this->c81_seqtranslr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c81_sequen,$this->c81_seqtranslr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6032,'$this->c81_sequen','I')");
       $resac = db_query("insert into db_acountkey values($acount,6033,'$this->c81_seqtranslr','I')");
       $resac = db_query("insert into db_acount values($acount,967,6032,'','".AddSlashes(pg_result($resaco,0,'c81_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,967,6033,'','".AddSlashes(pg_result($resaco,0,'c81_seqtranslr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c81_sequen=null,$c81_seqtranslr=null) { 
      $this->atualizacampos();
     $sql = " update conlancamlr set ";
     $virgula = "";
     if(trim($this->c81_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c81_sequen"])){ 
       $sql  .= $virgula." c81_sequen = $this->c81_sequen ";
       $virgula = ",";
       if(trim($this->c81_sequen) == null ){ 
         $this->erro_sql = " Campo Código Lançamento nao Informado.";
         $this->erro_campo = "c81_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c81_seqtranslr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c81_seqtranslr"])){ 
       $sql  .= $virgula." c81_seqtranslr = $this->c81_seqtranslr ";
       $virgula = ",";
       if(trim($this->c81_seqtranslr) == null ){ 
         $this->erro_sql = " Campo Sequencia Translr nao Informado.";
         $this->erro_campo = "c81_seqtranslr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c81_sequen!=null){
       $sql .= " c81_sequen = $this->c81_sequen";
     }
     if($c81_seqtranslr!=null){
       $sql .= " and  c81_seqtranslr = $this->c81_seqtranslr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c81_sequen,$this->c81_seqtranslr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6032,'$this->c81_sequen','A')");
         $resac = db_query("insert into db_acountkey values($acount,6033,'$this->c81_seqtranslr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c81_sequen"]))
           $resac = db_query("insert into db_acount values($acount,967,6032,'".AddSlashes(pg_result($resaco,$conresaco,'c81_sequen'))."','$this->c81_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c81_seqtranslr"]))
           $resac = db_query("insert into db_acount values($acount,967,6033,'".AddSlashes(pg_result($resaco,$conresaco,'c81_seqtranslr'))."','$this->c81_seqtranslr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos Automáticos - Previsão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c81_sequen."-".$this->c81_seqtranslr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos Automáticos - Previsão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c81_sequen."-".$this->c81_seqtranslr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c81_sequen."-".$this->c81_seqtranslr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c81_sequen=null,$c81_seqtranslr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c81_sequen,$c81_seqtranslr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6032,'$c81_sequen','E')");
         $resac = db_query("insert into db_acountkey values($acount,6033,'$c81_seqtranslr','E')");
         $resac = db_query("insert into db_acount values($acount,967,6032,'','".AddSlashes(pg_result($resaco,$iresaco,'c81_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,967,6033,'','".AddSlashes(pg_result($resaco,$iresaco,'c81_seqtranslr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conlancamlr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c81_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c81_sequen = $c81_sequen ";
        }
        if($c81_seqtranslr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c81_seqtranslr = $c81_seqtranslr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos Automáticos - Previsão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c81_sequen."-".$c81_seqtranslr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos Automáticos - Previsão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c81_sequen."-".$c81_seqtranslr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c81_sequen."-".$c81_seqtranslr;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamlr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c81_sequen=null,$c81_seqtranslr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamlr ";
     $sql .= "      inner join conlancamval  on  conlancamval.c69_sequen = conlancamlr.c81_sequen";
     $sql .= "      inner join contranslr  on  contranslr.c47_seqtranslr = conlancamlr.c81_seqtranslr";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamval.c69_codlan";
     $sql .= "      inner join conplanoexe  on  conplanoexe.c62_anousu = conlancamval.c69_anousu and  conplanoexe.c62_reduz = conlancamval.c69_credito";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = conlancamval.c69_codhist";
     $sql .= "      inner join contranslan  on  contranslan.c46_seqtranslan = contranslr.c47_seqtranslan";
     $sql2 = "";
     if($dbwhere==""){
       if($c81_sequen!=null ){
         $sql2 .= " where conlancamlr.c81_sequen = $c81_sequen "; 
       } 
       if($c81_seqtranslr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conlancamlr.c81_seqtranslr = $c81_seqtranslr "; 
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
   function sql_query_file ( $c81_sequen=null,$c81_seqtranslr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamlr ";
     $sql2 = "";
     if($dbwhere==""){
       if($c81_sequen!=null ){
         $sql2 .= " where conlancamlr.c81_sequen = $c81_sequen "; 
       } 
       if($c81_seqtranslr!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conlancamlr.c81_seqtranslr = $c81_seqtranslr "; 
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