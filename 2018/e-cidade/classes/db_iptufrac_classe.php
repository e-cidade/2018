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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptufrac
class cl_iptufrac { 
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
   var $j25_anousu = 0; 
   var $j25_matric = 0; 
   var $j25_idbql = 0; 
   var $j25_fracao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j25_anousu = int4 = Exercicio 
                 j25_matric = int4 = Matricula 
                 j25_idbql = int4 = Lote da Fracao 
                 j25_fracao = float8 = Fracao Ideal 
                 ";
   //funcao construtor da classe 
   function cl_iptufrac() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptufrac"); 
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
       $this->j25_anousu = ($this->j25_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j25_anousu"]:$this->j25_anousu);
       $this->j25_matric = ($this->j25_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j25_matric"]:$this->j25_matric);
       $this->j25_idbql = ($this->j25_idbql == ""?@$GLOBALS["HTTP_POST_VARS"]["j25_idbql"]:$this->j25_idbql);
       $this->j25_fracao = ($this->j25_fracao == ""?@$GLOBALS["HTTP_POST_VARS"]["j25_fracao"]:$this->j25_fracao);
     }else{
       $this->j25_anousu = ($this->j25_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j25_anousu"]:$this->j25_anousu);
       $this->j25_matric = ($this->j25_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j25_matric"]:$this->j25_matric);
     }
   }
   // funcao para inclusao
   function incluir ($j25_anousu,$j25_matric){ 
      $this->atualizacampos();
     if($this->j25_idbql == null ){ 
       $this->erro_sql = " Campo Lote da Fracao nao Informado.";
       $this->erro_campo = "j25_idbql";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j25_fracao == null ){ 
       $this->erro_sql = " Campo Fracao Ideal nao Informado.";
       $this->erro_campo = "j25_fracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j25_anousu = $j25_anousu; 
       $this->j25_matric = $j25_matric; 
     if(($this->j25_anousu == null) || ($this->j25_anousu == "") ){ 
       $this->erro_sql = " Campo j25_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j25_matric == null) || ($this->j25_matric == "") ){ 
       $this->erro_sql = " Campo j25_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptufrac(
                                       j25_anousu 
                                      ,j25_matric 
                                      ,j25_idbql 
                                      ,j25_fracao 
                       )
                values (
                                $this->j25_anousu 
                               ,$this->j25_matric 
                               ,$this->j25_idbql 
                               ,$this->j25_fracao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j25_anousu."-".$this->j25_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j25_anousu."-".$this->j25_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j25_anousu."-".$this->j25_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j25_anousu,$this->j25_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,673,'$this->j25_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,674,'$this->j25_matric','I')");
       $resac = db_query("insert into db_acount values($acount,125,673,'','".AddSlashes(pg_result($resaco,0,'j25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,125,674,'','".AddSlashes(pg_result($resaco,0,'j25_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,125,675,'','".AddSlashes(pg_result($resaco,0,'j25_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,125,676,'','".AddSlashes(pg_result($resaco,0,'j25_fracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j25_anousu=null,$j25_matric=null) { 
      $this->atualizacampos();
     $sql = " update iptufrac set ";
     $virgula = "";
     if(trim($this->j25_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j25_anousu"])){ 
       $sql  .= $virgula." j25_anousu = $this->j25_anousu ";
       $virgula = ",";
       if(trim($this->j25_anousu) == null ){ 
         $this->erro_sql = " Campo Exercicio nao Informado.";
         $this->erro_campo = "j25_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j25_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j25_matric"])){ 
       $sql  .= $virgula." j25_matric = $this->j25_matric ";
       $virgula = ",";
       if(trim($this->j25_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j25_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j25_idbql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j25_idbql"])){ 
       $sql  .= $virgula." j25_idbql = $this->j25_idbql ";
       $virgula = ",";
       if(trim($this->j25_idbql) == null ){ 
         $this->erro_sql = " Campo Lote da Fracao nao Informado.";
         $this->erro_campo = "j25_idbql";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j25_fracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j25_fracao"])){ 
       $sql  .= $virgula." j25_fracao = $this->j25_fracao ";
       $virgula = ",";
       if(trim($this->j25_fracao) == null ){ 
         $this->erro_sql = " Campo Fracao Ideal nao Informado.";
         $this->erro_campo = "j25_fracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j25_anousu!=null){
       $sql .= " j25_anousu = $this->j25_anousu";
     }
     if($j25_matric!=null){
       $sql .= " and  j25_matric = $this->j25_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j25_anousu,$this->j25_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,673,'$this->j25_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,674,'$this->j25_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j25_anousu"]))
           $resac = db_query("insert into db_acount values($acount,125,673,'".AddSlashes(pg_result($resaco,$conresaco,'j25_anousu'))."','$this->j25_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j25_matric"]))
           $resac = db_query("insert into db_acount values($acount,125,674,'".AddSlashes(pg_result($resaco,$conresaco,'j25_matric'))."','$this->j25_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j25_idbql"]))
           $resac = db_query("insert into db_acount values($acount,125,675,'".AddSlashes(pg_result($resaco,$conresaco,'j25_idbql'))."','$this->j25_idbql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j25_fracao"]))
           $resac = db_query("insert into db_acount values($acount,125,676,'".AddSlashes(pg_result($resaco,$conresaco,'j25_fracao'))."','$this->j25_fracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j25_anousu."-".$this->j25_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j25_anousu."-".$this->j25_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j25_anousu."-".$this->j25_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j25_anousu=null,$j25_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j25_anousu,$j25_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,673,'$j25_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,674,'$j25_matric','E')");
         $resac = db_query("insert into db_acount values($acount,125,673,'','".AddSlashes(pg_result($resaco,$iresaco,'j25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,125,674,'','".AddSlashes(pg_result($resaco,$iresaco,'j25_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,125,675,'','".AddSlashes(pg_result($resaco,$iresaco,'j25_idbql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,125,676,'','".AddSlashes(pg_result($resaco,$iresaco,'j25_fracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptufrac
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j25_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j25_anousu = $j25_anousu ";
        }
        if($j25_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j25_matric = $j25_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j25_anousu."-".$j25_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j25_anousu."-".$j25_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j25_anousu."-".$j25_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptufrac";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>