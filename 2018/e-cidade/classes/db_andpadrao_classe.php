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

//MODULO: protocolo
//CLASSE DA ENTIDADE andpadrao
class cl_andpadrao { 
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
   var $p53_codigo = 0; 
   var $p53_coddepto = 0; 
   var $p53_dias = 0; 
   var $p53_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p53_codigo = int4 = código do tipo 
                 p53_coddepto = int4 = código do departamento 
                 p53_dias = int4 = quantidade de dias 
                 p53_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_andpadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("andpadrao"); 
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
       $this->p53_codigo = ($this->p53_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p53_codigo"]:$this->p53_codigo);
       $this->p53_coddepto = ($this->p53_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["p53_coddepto"]:$this->p53_coddepto);
       $this->p53_dias = ($this->p53_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["p53_dias"]:$this->p53_dias);
       $this->p53_ordem = ($this->p53_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["p53_ordem"]:$this->p53_ordem);
     }else{
       $this->p53_codigo = ($this->p53_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p53_codigo"]:$this->p53_codigo);
       $this->p53_ordem = ($this->p53_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["p53_ordem"]:$this->p53_ordem);
     }
   }
   // funcao para inclusao
   function incluir ($p53_codigo,$p53_ordem){ 
      $this->atualizacampos();
     if($this->p53_coddepto == null ){ 
       $this->erro_sql = " Campo código do departamento nao Informado.";
       $this->erro_campo = "p53_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p53_dias == null ){ 
       $this->erro_sql = " Campo quantidade de dias nao Informado.";
       $this->erro_campo = "p53_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->p53_codigo = $p53_codigo; 
       $this->p53_ordem = $p53_ordem; 
     if(($this->p53_codigo == null) || ($this->p53_codigo == "") ){ 
       $this->erro_sql = " Campo p53_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p53_ordem == null) || ($this->p53_ordem == "") ){ 
       $this->erro_sql = " Campo p53_ordem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into andpadrao(
                                       p53_codigo 
                                      ,p53_coddepto 
                                      ,p53_dias 
                                      ,p53_ordem 
                       )
                values (
                                $this->p53_codigo 
                               ,$this->p53_coddepto 
                               ,$this->p53_dias 
                               ,$this->p53_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Andamento Padrão do Processo ($this->p53_codigo."-".$this->p53_ordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Andamento Padrão do Processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Andamento Padrão do Processo ($this->p53_codigo."-".$this->p53_ordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p53_codigo."-".$this->p53_ordem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p53_codigo,$this->p53_ordem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2440,'$this->p53_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4672,'$this->p53_ordem','I')");
       $resac = db_query("insert into db_acount values($acount,396,2440,'','".AddSlashes(pg_result($resaco,0,'p53_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,396,2441,'','".AddSlashes(pg_result($resaco,0,'p53_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,396,2443,'','".AddSlashes(pg_result($resaco,0,'p53_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,396,4672,'','".AddSlashes(pg_result($resaco,0,'p53_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p53_codigo=null,$p53_ordem=null) { 
      $this->atualizacampos();
     $sql = " update andpadrao set ";
     $virgula = "";
     if(trim($this->p53_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p53_codigo"])){ 
       $sql  .= $virgula." p53_codigo = $this->p53_codigo ";
       $virgula = ",";
       if(trim($this->p53_codigo) == null ){ 
         $this->erro_sql = " Campo código do tipo nao Informado.";
         $this->erro_campo = "p53_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p53_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p53_coddepto"])){ 
       $sql  .= $virgula." p53_coddepto = $this->p53_coddepto ";
       $virgula = ",";
       if(trim($this->p53_coddepto) == null ){ 
         $this->erro_sql = " Campo código do departamento nao Informado.";
         $this->erro_campo = "p53_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p53_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p53_dias"])){ 
       $sql  .= $virgula." p53_dias = $this->p53_dias ";
       $virgula = ",";
       if(trim($this->p53_dias) == null ){ 
         $this->erro_sql = " Campo quantidade de dias nao Informado.";
         $this->erro_campo = "p53_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p53_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p53_ordem"])){ 
       $sql  .= $virgula." p53_ordem = $this->p53_ordem ";
       $virgula = ",";
       if(trim($this->p53_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "p53_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p53_codigo!=null){
       $sql .= " p53_codigo = $this->p53_codigo";
     }
     if($p53_ordem!=null){
       $sql .= " and  p53_ordem = $this->p53_ordem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p53_codigo,$this->p53_ordem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2440,'$this->p53_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4672,'$this->p53_ordem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p53_codigo"]))
           $resac = db_query("insert into db_acount values($acount,396,2440,'".AddSlashes(pg_result($resaco,$conresaco,'p53_codigo'))."','$this->p53_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p53_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,396,2441,'".AddSlashes(pg_result($resaco,$conresaco,'p53_coddepto'))."','$this->p53_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p53_dias"]))
           $resac = db_query("insert into db_acount values($acount,396,2443,'".AddSlashes(pg_result($resaco,$conresaco,'p53_dias'))."','$this->p53_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p53_ordem"]))
           $resac = db_query("insert into db_acount values($acount,396,4672,'".AddSlashes(pg_result($resaco,$conresaco,'p53_ordem'))."','$this->p53_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento Padrão do Processo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p53_codigo."-".$this->p53_ordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento Padrão do Processo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p53_codigo."-".$this->p53_ordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p53_codigo."-".$this->p53_ordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p53_codigo=null,$p53_ordem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p53_codigo,$p53_ordem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2440,'$p53_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4672,'$p53_ordem','E')");
         $resac = db_query("insert into db_acount values($acount,396,2440,'','".AddSlashes(pg_result($resaco,$iresaco,'p53_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,396,2441,'','".AddSlashes(pg_result($resaco,$iresaco,'p53_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,396,2443,'','".AddSlashes(pg_result($resaco,$iresaco,'p53_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,396,4672,'','".AddSlashes(pg_result($resaco,$iresaco,'p53_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from andpadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p53_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p53_codigo = $p53_codigo ";
        }
        if($p53_ordem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p53_ordem = $p53_ordem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento Padrão do Processo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p53_codigo."-".$p53_ordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento Padrão do Processo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p53_codigo."-".$p53_ordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p53_codigo."-".$p53_ordem;
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
        $this->erro_sql   = "Record Vazio na Tabela:andpadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p53_codigo=null,$p53_ordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from andpadrao ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = andpadrao.p53_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = andpadrao.p53_codigo";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join db_config  as a on   a.codigo = tipoproc.p51_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($p53_codigo!=null ){
         $sql2 .= " where andpadrao.p53_codigo = $p53_codigo "; 
       } 
       if($p53_ordem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " andpadrao.p53_ordem = $p53_ordem "; 
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
   function sql_query_file ( $p53_codigo=null,$p53_ordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from andpadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($p53_codigo!=null ){
         $sql2 .= " where andpadrao.p53_codigo = $p53_codigo "; 
       } 
       if($p53_ordem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " andpadrao.p53_ordem = $p53_ordem "; 
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