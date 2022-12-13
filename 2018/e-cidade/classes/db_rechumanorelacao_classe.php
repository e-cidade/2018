<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: educação
//CLASSE DA ENTIDADE rechumanorelacao
class cl_rechumanorelacao { 
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
   var $ed03_i_codigo = 0; 
   var $ed03_i_rechumanoativ = 0; 
   var $ed03_i_relacaotrabalho = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed03_i_codigo = int8 = Código 
                 ed03_i_rechumanoativ = int8 = Atividade 
                 ed03_i_relacaotrabalho = int8 = Relação de Trabalho 
                 ";
   //funcao construtor da classe 
   function cl_rechumanorelacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumanorelacao"); 
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
       $this->ed03_i_codigo = ($this->ed03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed03_i_codigo"]:$this->ed03_i_codigo);
       $this->ed03_i_rechumanoativ = ($this->ed03_i_rechumanoativ == ""?@$GLOBALS["HTTP_POST_VARS"]["ed03_i_rechumanoativ"]:$this->ed03_i_rechumanoativ);
       $this->ed03_i_relacaotrabalho = ($this->ed03_i_relacaotrabalho == ""?@$GLOBALS["HTTP_POST_VARS"]["ed03_i_relacaotrabalho"]:$this->ed03_i_relacaotrabalho);
     }else{
       $this->ed03_i_codigo = ($this->ed03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed03_i_codigo"]:$this->ed03_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed03_i_codigo){ 
      $this->atualizacampos();
     if($this->ed03_i_rechumanoativ == null ){ 
       $this->erro_sql = " Campo Atividade não informado.";
       $this->erro_campo = "ed03_i_rechumanoativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed03_i_relacaotrabalho == null ){ 
       $this->erro_sql = " Campo Relação de Trabalho não informado.";
       $this->erro_campo = "ed03_i_relacaotrabalho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed03_i_codigo == "" || $ed03_i_codigo == null ){
       $result = db_query("select nextval('rechumanorelacao_ed03_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumanorelacao_ed03_i_codigo_seq do campo: ed03_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed03_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rechumanorelacao_ed03_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed03_i_codigo)){
         $this->erro_sql = " Campo ed03_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed03_i_codigo = $ed03_i_codigo; 
       }
     }
     if(($this->ed03_i_codigo == null) || ($this->ed03_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed03_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumanorelacao(
                                       ed03_i_codigo 
                                      ,ed03_i_rechumanoativ 
                                      ,ed03_i_relacaotrabalho 
                       )
                values (
                                $this->ed03_i_codigo 
                               ,$this->ed03_i_rechumanoativ 
                               ,$this->ed03_i_relacaotrabalho 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Relações de Trabalho ligadas a atividade ($this->ed03_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Relações de Trabalho ligadas a atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Relações de Trabalho ligadas a atividade ($this->ed03_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed03_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed03_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008555,'$this->ed03_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010098,1008555,'','".AddSlashes(pg_result($resaco,0,'ed03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010098,1008556,'','".AddSlashes(pg_result($resaco,0,'ed03_i_rechumanoativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010098,1008557,'','".AddSlashes(pg_result($resaco,0,'ed03_i_relacaotrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed03_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rechumanorelacao set ";
     $virgula = "";
     if(trim($this->ed03_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed03_i_codigo"])){ 
       $sql  .= $virgula." ed03_i_codigo = $this->ed03_i_codigo ";
       $virgula = ",";
       if(trim($this->ed03_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed03_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed03_i_rechumanoativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed03_i_rechumanoativ"])){ 
       $sql  .= $virgula." ed03_i_rechumanoativ = $this->ed03_i_rechumanoativ ";
       $virgula = ",";
       if(trim($this->ed03_i_rechumanoativ) == null ){ 
         $this->erro_sql = " Campo Atividade não informado.";
         $this->erro_campo = "ed03_i_rechumanoativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed03_i_relacaotrabalho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed03_i_relacaotrabalho"])){ 
       $sql  .= $virgula." ed03_i_relacaotrabalho = $this->ed03_i_relacaotrabalho ";
       $virgula = ",";
       if(trim($this->ed03_i_relacaotrabalho) == null ){ 
         $this->erro_sql = " Campo Relação de Trabalho não informado.";
         $this->erro_campo = "ed03_i_relacaotrabalho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed03_i_codigo!=null){
       $sql .= " ed03_i_codigo = $this->ed03_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed03_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008555,'$this->ed03_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed03_i_codigo"]) || $this->ed03_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010098,1008555,'".AddSlashes(pg_result($resaco,$conresaco,'ed03_i_codigo'))."','$this->ed03_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed03_i_rechumanoativ"]) || $this->ed03_i_rechumanoativ != "")
             $resac = db_query("insert into db_acount values($acount,1010098,1008556,'".AddSlashes(pg_result($resaco,$conresaco,'ed03_i_rechumanoativ'))."','$this->ed03_i_rechumanoativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed03_i_relacaotrabalho"]) || $this->ed03_i_relacaotrabalho != "")
             $resac = db_query("insert into db_acount values($acount,1010098,1008557,'".AddSlashes(pg_result($resaco,$conresaco,'ed03_i_relacaotrabalho'))."','$this->ed03_i_relacaotrabalho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relações de Trabalho ligadas a atividade não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Relações de Trabalho ligadas a atividade não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed03_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed03_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008555,'$ed03_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010098,1008555,'','".AddSlashes(pg_result($resaco,$iresaco,'ed03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010098,1008556,'','".AddSlashes(pg_result($resaco,$iresaco,'ed03_i_rechumanoativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010098,1008557,'','".AddSlashes(pg_result($resaco,$iresaco,'ed03_i_relacaotrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rechumanorelacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed03_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed03_i_codigo = $ed03_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relações de Trabalho ligadas a atividade não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Relações de Trabalho ligadas a atividade não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rechumanorelacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed03_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rechumanorelacao ";
     $sql .= "      inner join rechumanoativ  on  rechumanoativ.ed22_i_codigo = rechumanorelacao.ed03_i_rechumanoativ";
     $sql .= "      inner join relacaotrabalho  on  relacaotrabalho.ed23_i_codigo = rechumanorelacao.ed03_i_relacaotrabalho";
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = rechumanoativ.ed22_i_rechumanoescola";
     $sql .= "      inner join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = relacaotrabalho.ed23_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join areatrabalho  on  areatrabalho.ed25_i_codigo = relacaotrabalho.ed23_i_areatrabalho";
     $sql .= "      inner join regimetrabalho  on  regimetrabalho.ed24_i_codigo = relacaotrabalho.ed23_i_regimetrabalho";
     $sql .= "      inner join rechumanoescola  as a on   a.ed75_i_codigo = relacaotrabalho.ed23_i_rechumanoescola";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed03_i_codigo)) {
         $sql2 .= " where rechumanorelacao.ed03_i_codigo = $ed03_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($ed03_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from rechumanorelacao ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed03_i_codigo)){
        $sql2 .= " where rechumanorelacao.ed03_i_codigo = $ed03_i_codigo "; 
      } 
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

  public function sql_funcao_relacao ($ed03_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from rechumanorelacao ";
    $sql .= " inner join rechumanoativ   on rechumanoativ.ed22_i_codigo = rechumanorelacao.ed03_i_rechumanoativ ";
    $sql .= " inner join relacaotrabalho on relacaotrabalho.ed23_i_codigo = rechumanorelacao.ed03_i_relacaotrabalho ";

    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed03_i_codigo)){
        $sql2 .= " where rechumanorelacao.ed03_i_codigo = $ed03_i_codigo "; 
      } 
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

}
