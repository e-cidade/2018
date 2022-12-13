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

//MODULO: compras
//CLASSE DA ENTIDADE pccontrdot
class cl_pccontrdot { 
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
   var $p73_codcontr = 0; 
   var $p73_anousu = 0; 
   var $p73_coddot = 0; 
   var $p73_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p73_codcontr = int4 = Código do contrato 
                 p73_anousu = int4 = Exercício 
                 p73_coddot = int4 = Reduzido 
                 p73_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_pccontrdot() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pccontrdot"); 
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
       $this->p73_codcontr = ($this->p73_codcontr == ""?@$GLOBALS["HTTP_POST_VARS"]["p73_codcontr"]:$this->p73_codcontr);
       $this->p73_anousu = ($this->p73_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["p73_anousu"]:$this->p73_anousu);
       $this->p73_coddot = ($this->p73_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["p73_coddot"]:$this->p73_coddot);
       $this->p73_valor = ($this->p73_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["p73_valor"]:$this->p73_valor);
     }else{
       $this->p73_codcontr = ($this->p73_codcontr == ""?@$GLOBALS["HTTP_POST_VARS"]["p73_codcontr"]:$this->p73_codcontr);
       $this->p73_coddot = ($this->p73_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["p73_coddot"]:$this->p73_coddot);
     }
   }
   // funcao para inclusao
   function incluir ($p73_codcontr,$p73_coddot){ 
      $this->atualizacampos();
     if($this->p73_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "p73_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p73_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "p73_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->p73_codcontr = $p73_codcontr; 
       $this->p73_coddot = $p73_coddot; 
     if(($this->p73_codcontr == null) || ($this->p73_codcontr == "") ){ 
       $this->erro_sql = " Campo p73_codcontr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p73_coddot == null) || ($this->p73_coddot == "") ){ 
       $this->erro_sql = " Campo p73_coddot nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pccontrdot(
                                       p73_codcontr 
                                      ,p73_anousu 
                                      ,p73_coddot 
                                      ,p73_valor 
                       )
                values (
                                $this->p73_codcontr 
                               ,$this->p73_anousu 
                               ,$this->p73_coddot 
                               ,$this->p73_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dotação dos contratos ($this->p73_codcontr."-".$this->p73_coddot) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dotação dos contratos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dotação dos contratos ($this->p73_codcontr."-".$this->p73_coddot) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p73_codcontr."-".$this->p73_coddot;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p73_codcontr,$this->p73_coddot));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6118,'$this->p73_codcontr','I')");
       $resac = db_query("insert into db_acountkey values($acount,6120,'$this->p73_coddot','I')");
       $resac = db_query("insert into db_acount values($acount,984,6118,'','".AddSlashes(pg_result($resaco,0,'p73_codcontr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,984,6119,'','".AddSlashes(pg_result($resaco,0,'p73_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,984,6120,'','".AddSlashes(pg_result($resaco,0,'p73_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,984,6121,'','".AddSlashes(pg_result($resaco,0,'p73_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p73_codcontr=null,$p73_coddot=null) { 
      $this->atualizacampos();
     $sql = " update pccontrdot set ";
     $virgula = "";
     if(trim($this->p73_codcontr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p73_codcontr"])){ 
       $sql  .= $virgula." p73_codcontr = $this->p73_codcontr ";
       $virgula = ",";
       if(trim($this->p73_codcontr) == null ){ 
         $this->erro_sql = " Campo Código do contrato nao Informado.";
         $this->erro_campo = "p73_codcontr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p73_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p73_anousu"])){ 
       $sql  .= $virgula." p73_anousu = $this->p73_anousu ";
       $virgula = ",";
       if(trim($this->p73_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "p73_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p73_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p73_coddot"])){ 
       $sql  .= $virgula." p73_coddot = $this->p73_coddot ";
       $virgula = ",";
       if(trim($this->p73_coddot) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "p73_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p73_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p73_valor"])){ 
       $sql  .= $virgula." p73_valor = $this->p73_valor ";
       $virgula = ",";
       if(trim($this->p73_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "p73_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p73_codcontr!=null){
       $sql .= " p73_codcontr = $this->p73_codcontr";
     }
     if($p73_coddot!=null){
       $sql .= " and  p73_coddot = $this->p73_coddot";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p73_codcontr,$this->p73_coddot));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6118,'$this->p73_codcontr','A')");
         $resac = db_query("insert into db_acountkey values($acount,6120,'$this->p73_coddot','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p73_codcontr"]))
           $resac = db_query("insert into db_acount values($acount,984,6118,'".AddSlashes(pg_result($resaco,$conresaco,'p73_codcontr'))."','$this->p73_codcontr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p73_anousu"]))
           $resac = db_query("insert into db_acount values($acount,984,6119,'".AddSlashes(pg_result($resaco,$conresaco,'p73_anousu'))."','$this->p73_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p73_coddot"]))
           $resac = db_query("insert into db_acount values($acount,984,6120,'".AddSlashes(pg_result($resaco,$conresaco,'p73_coddot'))."','$this->p73_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p73_valor"]))
           $resac = db_query("insert into db_acount values($acount,984,6121,'".AddSlashes(pg_result($resaco,$conresaco,'p73_valor'))."','$this->p73_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotação dos contratos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p73_codcontr."-".$this->p73_coddot;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotação dos contratos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p73_codcontr."-".$this->p73_coddot;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p73_codcontr."-".$this->p73_coddot;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p73_codcontr=null,$p73_coddot=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p73_codcontr,$p73_coddot));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6118,'$p73_codcontr','E')");
         $resac = db_query("insert into db_acountkey values($acount,6120,'$p73_coddot','E')");
         $resac = db_query("insert into db_acount values($acount,984,6118,'','".AddSlashes(pg_result($resaco,$iresaco,'p73_codcontr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,984,6119,'','".AddSlashes(pg_result($resaco,$iresaco,'p73_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,984,6120,'','".AddSlashes(pg_result($resaco,$iresaco,'p73_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,984,6121,'','".AddSlashes(pg_result($resaco,$iresaco,'p73_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pccontrdot
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p73_codcontr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p73_codcontr = $p73_codcontr ";
        }
        if($p73_coddot != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p73_coddot = $p73_coddot ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotação dos contratos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p73_codcontr."-".$p73_coddot;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotação dos contratos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p73_codcontr."-".$p73_coddot;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p73_codcontr."-".$p73_coddot;
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
        $this->erro_sql   = "Record Vazio na Tabela:pccontrdot";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p73_codcontr=null,$p73_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pccontrdot ";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = pccontrdot.p73_anousu and  orcdotacao.o58_coddot = pccontrdot.p73_coddot";
     $sql .= "      inner join pccontratos  on  pccontratos.p71_codcontr = pccontrdot.p73_codcontr";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  as b on   b.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  as c on   c.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  as d on   d.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  as d on   d.o54_anousu = orcdotacao.o58_anousu and   d.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  as d on   d.o56_codele = orcdotacao.o58_codele and d.o56_anousu = orcdotacao.o58_anousu ";
     $sql .= "      inner join orcprojativ  as d on   d.o55_anousu = orcdotacao.o58_anousu and   d.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcdotacao.o58_anousu and   d.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  as d on   d.o41_anousu = orcdotacao.o58_anousu and   d.o41_orgao = orcdotacao.o58_orgao and   d.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pccontratos.p71_numcgm";
     $sql .= "      inner join pctipocontrato  on  pctipocontrato.p70_codtipo = pccontratos.p71_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($p73_codcontr!=null ){
         $sql2 .= " where pccontrdot.p73_codcontr = $p73_codcontr "; 
       } 
       if($p73_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pccontrdot.p73_coddot = $p73_coddot "; 
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
   function sql_query_file ( $p73_codcontr=null,$p73_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pccontrdot ";
     $sql2 = "";
     if($dbwhere==""){
       if($p73_codcontr!=null ){
         $sql2 .= " where pccontrdot.p73_codcontr = $p73_codcontr "; 
       } 
       if($p73_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pccontrdot.p73_coddot = $p73_coddot "; 
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