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

//MODULO: fiscal
//CLASSE DA ENTIDADE sanicalc
class cl_sanicalc { 
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
   var $y84_codsani = 0; 
   var $y84_anousu = 0; 
   var $y84_numpre = 0; 
   var $y84_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y84_codsani = int4 = Código do Alvará sanitário 
                 y84_anousu = int4 = Ano do cálculo 
                 y84_numpre = int4 = código de Arrecadação do Cálculo 
                 y84_valor = float8 = Valor do Cálculo 
                 ";
   //funcao construtor da classe 
   function cl_sanicalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sanicalc"); 
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
       $this->y84_codsani = ($this->y84_codsani == ""?@$GLOBALS["HTTP_POST_VARS"]["y84_codsani"]:$this->y84_codsani);
       $this->y84_anousu = ($this->y84_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["y84_anousu"]:$this->y84_anousu);
       $this->y84_numpre = ($this->y84_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["y84_numpre"]:$this->y84_numpre);
       $this->y84_valor = ($this->y84_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["y84_valor"]:$this->y84_valor);
     }else{
       $this->y84_codsani = ($this->y84_codsani == ""?@$GLOBALS["HTTP_POST_VARS"]["y84_codsani"]:$this->y84_codsani);
       $this->y84_anousu = ($this->y84_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["y84_anousu"]:$this->y84_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($y84_codsani,$y84_anousu){ 
      $this->atualizacampos();
     if($this->y84_numpre == null ){ 
       $this->erro_sql = " Campo código de Arrecadação do Cálculo nao Informado.";
       $this->erro_campo = "y84_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y84_valor == null ){ 
       $this->erro_sql = " Campo Valor do Cálculo nao Informado.";
       $this->erro_campo = "y84_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->y84_codsani = $y84_codsani; 
       $this->y84_anousu = $y84_anousu; 
     if(($this->y84_codsani == null) || ($this->y84_codsani == "") ){ 
       $this->erro_sql = " Campo y84_codsani nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y84_anousu == null) || ($this->y84_anousu == "") ){ 
       $this->erro_sql = " Campo y84_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sanicalc(
                                       y84_codsani 
                                      ,y84_anousu 
                                      ,y84_numpre 
                                      ,y84_valor 
                       )
                values (
                                $this->y84_codsani 
                               ,$this->y84_anousu 
                               ,$this->y84_numpre 
                               ,$this->y84_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sanicalc ($this->y84_codsani."-".$this->y84_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sanicalc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sanicalc ($this->y84_codsani."-".$this->y84_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y84_codsani."-".$this->y84_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y84_codsani,$this->y84_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4888,'$this->y84_codsani','I')");
       $resac = db_query("insert into db_acountkey values($acount,4889,'$this->y84_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,666,4888,'','".AddSlashes(pg_result($resaco,0,'y84_codsani'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,666,4889,'','".AddSlashes(pg_result($resaco,0,'y84_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,666,4890,'','".AddSlashes(pg_result($resaco,0,'y84_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,666,4891,'','".AddSlashes(pg_result($resaco,0,'y84_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y84_codsani=null,$y84_anousu=null) { 
      $this->atualizacampos();
     $sql = " update sanicalc set ";
     $virgula = "";
     if(trim($this->y84_codsani)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y84_codsani"])){ 
       $sql  .= $virgula." y84_codsani = $this->y84_codsani ";
       $virgula = ",";
       if(trim($this->y84_codsani) == null ){ 
         $this->erro_sql = " Campo Código do Alvará sanitário nao Informado.";
         $this->erro_campo = "y84_codsani";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y84_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y84_anousu"])){ 
       $sql  .= $virgula." y84_anousu = $this->y84_anousu ";
       $virgula = ",";
       if(trim($this->y84_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do cálculo nao Informado.";
         $this->erro_campo = "y84_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y84_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y84_numpre"])){ 
       $sql  .= $virgula." y84_numpre = $this->y84_numpre ";
       $virgula = ",";
       if(trim($this->y84_numpre) == null ){ 
         $this->erro_sql = " Campo código de Arrecadação do Cálculo nao Informado.";
         $this->erro_campo = "y84_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y84_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y84_valor"])){ 
       $sql  .= $virgula." y84_valor = $this->y84_valor ";
       $virgula = ",";
       if(trim($this->y84_valor) == null ){ 
         $this->erro_sql = " Campo Valor do Cálculo nao Informado.";
         $this->erro_campo = "y84_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y84_codsani!=null){
       $sql .= " y84_codsani = $this->y84_codsani";
     }
     if($y84_anousu!=null){
       $sql .= " and  y84_anousu = $this->y84_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y84_codsani,$this->y84_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4888,'$this->y84_codsani','A')");
         $resac = db_query("insert into db_acountkey values($acount,4889,'$this->y84_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y84_codsani"]))
           $resac = db_query("insert into db_acount values($acount,666,4888,'".AddSlashes(pg_result($resaco,$conresaco,'y84_codsani'))."','$this->y84_codsani',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y84_anousu"]))
           $resac = db_query("insert into db_acount values($acount,666,4889,'".AddSlashes(pg_result($resaco,$conresaco,'y84_anousu'))."','$this->y84_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y84_numpre"]))
           $resac = db_query("insert into db_acount values($acount,666,4890,'".AddSlashes(pg_result($resaco,$conresaco,'y84_numpre'))."','$this->y84_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y84_valor"]))
           $resac = db_query("insert into db_acount values($acount,666,4891,'".AddSlashes(pg_result($resaco,$conresaco,'y84_valor'))."','$this->y84_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sanicalc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y84_codsani."-".$this->y84_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sanicalc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y84_codsani."-".$this->y84_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y84_codsani."-".$this->y84_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y84_codsani=null,$y84_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y84_codsani,$y84_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4888,'$y84_codsani','E')");
         $resac = db_query("insert into db_acountkey values($acount,4889,'$y84_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,666,4888,'','".AddSlashes(pg_result($resaco,$iresaco,'y84_codsani'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,666,4889,'','".AddSlashes(pg_result($resaco,$iresaco,'y84_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,666,4890,'','".AddSlashes(pg_result($resaco,$iresaco,'y84_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,666,4891,'','".AddSlashes(pg_result($resaco,$iresaco,'y84_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sanicalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y84_codsani != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y84_codsani = $y84_codsani ";
        }
        if($y84_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y84_anousu = $y84_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sanicalc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y84_codsani."-".$y84_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sanicalc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y84_codsani."-".$y84_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y84_codsani."-".$y84_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:sanicalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>