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
//MODULO: habitacao
//CLASSE DA ENTIDADE avaliacaopergunta
class cl_avaliacaopergunta { 
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
   var $db103_sequencial = 0; 
   var $db103_avaliacaotiporesposta = 0; 
   var $db103_avaliacaogrupopergunta = 0; 
   var $db103_descricao = null; 
   var $db103_identificador = null; 
   var $db103_obrigatoria = 'f'; 
   var $db103_ativo = 'f'; 
   var $db103_ordem = 0; 
   var $db103_tipo = 0; 
   var $db103_mascara = null; 
   var $db103_dblayoutcampo = 0; 
   var $db103_perguntaidentificadora = 'f'; 
   var $db103_camposql = null; 
   var $db103_identificadorcampo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db103_sequencial = int4 = Sequencial 
                 db103_avaliacaotiporesposta = int4 = Avaliação Tipo Resposta 
                 db103_avaliacaogrupopergunta = int4 = Avaliação Grupo Pergunta 
                 db103_descricao = text = Descrição 
                 db103_identificador = varchar(50) = Identificador 
                 db103_obrigatoria = bool = Obrigatória 
                 db103_ativo = bool = Ativo 
                 db103_ordem = int4 = Ordem 
                 db103_tipo = int4 = Tipo 
                 db103_mascara = varchar(40) = Máscara 
                 db103_dblayoutcampo = int4 = Coluna Layout 
                 db103_perguntaidentificadora = bool = Pergunta Identificadora 
                 db103_camposql = varchar(40) = Campor Carga 
                 db103_identificadorcampo = varchar(255) = Identificador Campo 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaopergunta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaopergunta"); 
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
       $this->db103_sequencial = ($this->db103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_sequencial"]:$this->db103_sequencial);
       $this->db103_avaliacaotiporesposta = ($this->db103_avaliacaotiporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_avaliacaotiporesposta"]:$this->db103_avaliacaotiporesposta);
       $this->db103_avaliacaogrupopergunta = ($this->db103_avaliacaogrupopergunta == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_avaliacaogrupopergunta"]:$this->db103_avaliacaogrupopergunta);
       $this->db103_descricao = ($this->db103_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_descricao"]:$this->db103_descricao);
       $this->db103_identificador = ($this->db103_identificador == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_identificador"]:$this->db103_identificador);
       $this->db103_obrigatoria = ($this->db103_obrigatoria == "f"?@$GLOBALS["HTTP_POST_VARS"]["db103_obrigatoria"]:$this->db103_obrigatoria);
       $this->db103_ativo = ($this->db103_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["db103_ativo"]:$this->db103_ativo);
       $this->db103_ordem = ($this->db103_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_ordem"]:$this->db103_ordem);
       $this->db103_tipo = ($this->db103_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_tipo"]:$this->db103_tipo);
       $this->db103_mascara = ($this->db103_mascara == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_mascara"]:$this->db103_mascara);
       $this->db103_dblayoutcampo = ($this->db103_dblayoutcampo == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_dblayoutcampo"]:$this->db103_dblayoutcampo);
       $this->db103_perguntaidentificadora = ($this->db103_perguntaidentificadora == "f"?@$GLOBALS["HTTP_POST_VARS"]["db103_perguntaidentificadora"]:$this->db103_perguntaidentificadora);
       $this->db103_camposql = ($this->db103_camposql == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_camposql"]:$this->db103_camposql);
       $this->db103_identificadorcampo = ($this->db103_identificadorcampo == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_identificadorcampo"]:$this->db103_identificadorcampo);
     }else{
       $this->db103_sequencial = ($this->db103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db103_sequencial"]:$this->db103_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db103_sequencial){ 
      $this->atualizacampos();
     if($this->db103_avaliacaotiporesposta == null ){ 
       $this->erro_sql = " Campo Avaliação Tipo Resposta não informado.";
       $this->erro_campo = "db103_avaliacaotiporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db103_avaliacaogrupopergunta == null ){ 
       $this->erro_sql = " Campo Avaliação Grupo Pergunta não informado.";
       $this->erro_campo = "db103_avaliacaogrupopergunta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db103_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "db103_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db103_obrigatoria == null ){ 
       $this->erro_sql = " Campo Obrigatória não informado.";
       $this->erro_campo = "db103_obrigatoria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db103_ativo == null ){ 
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "db103_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db103_ordem === null ){
       $this->erro_sql = " Campo Ordem não informado.";
       $this->erro_campo = "db103_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db103_tipo == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "db103_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db103_dblayoutcampo == null ){ 
       $this->db103_dblayoutcampo = "0";
     }
     if($this->db103_perguntaidentificadora == null ){ 
       $this->db103_perguntaidentificadora = "false";
     }
     if($db103_sequencial == "" || $db103_sequencial == null ){
       $result = db_query("select nextval('avaliacaopergunta_db103_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaopergunta_db103_sequencial_seq do campo: db103_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db103_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaopergunta_db103_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db103_sequencial)){
         $this->erro_sql = " Campo db103_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db103_sequencial = $db103_sequencial; 
       }
     }
     if(($this->db103_sequencial == null) || ($this->db103_sequencial == "") ){ 
       $this->erro_sql = " Campo db103_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaopergunta(
                                       db103_sequencial 
                                      ,db103_avaliacaotiporesposta 
                                      ,db103_avaliacaogrupopergunta 
                                      ,db103_descricao 
                                      ,db103_identificador 
                                      ,db103_obrigatoria 
                                      ,db103_ativo 
                                      ,db103_ordem 
                                      ,db103_tipo 
                                      ,db103_mascara 
                                      ,db103_dblayoutcampo 
                                      ,db103_perguntaidentificadora 
                                      ,db103_camposql 
                                      ,db103_identificadorcampo 
                       )
                values (
                                $this->db103_sequencial 
                               ,$this->db103_avaliacaotiporesposta 
                               ,$this->db103_avaliacaogrupopergunta 
                               ,'$this->db103_descricao' 
                               ,'$this->db103_identificador' 
                               ,'$this->db103_obrigatoria' 
                               ,'$this->db103_ativo' 
                               ,$this->db103_ordem 
                               ,$this->db103_tipo 
                               ,'$this->db103_mascara' 
                               ,$this->db103_dblayoutcampo 
                               ,'$this->db103_perguntaidentificadora' 
                               ,'$this->db103_camposql' 
                               ,'$this->db103_identificadorcampo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação Pergunta ($this->db103_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação Pergunta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação Pergunta ($this->db103_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db103_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db103_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16915,'$this->db103_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2983,16915,'','".AddSlashes(pg_result($resaco,0,'db103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,17046,'','".AddSlashes(pg_result($resaco,0,'db103_avaliacaotiporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,16916,'','".AddSlashes(pg_result($resaco,0,'db103_avaliacaogrupopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,16917,'','".AddSlashes(pg_result($resaco,0,'db103_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,19378,'','".AddSlashes(pg_result($resaco,0,'db103_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,16918,'','".AddSlashes(pg_result($resaco,0,'db103_obrigatoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,16919,'','".AddSlashes(pg_result($resaco,0,'db103_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,17023,'','".AddSlashes(pg_result($resaco,0,'db103_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,21839,'','".AddSlashes(pg_result($resaco,0,'db103_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,21840,'','".AddSlashes(pg_result($resaco,0,'db103_mascara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,1009307,'','".AddSlashes(pg_result($resaco,0,'db103_dblayoutcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,1009305,'','".AddSlashes(pg_result($resaco,0,'db103_perguntaidentificadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,1009304,'','".AddSlashes(pg_result($resaco,0,'db103_camposql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2983,1009512,'','".AddSlashes(pg_result($resaco,0,'db103_identificadorcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db103_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaopergunta set ";
     $virgula = "";
     if(trim($this->db103_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_sequencial"])){ 
       $sql  .= $virgula." db103_sequencial = $this->db103_sequencial ";
       $virgula = ",";
       if(trim($this->db103_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "db103_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_avaliacaotiporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_avaliacaotiporesposta"])){ 
       $sql  .= $virgula." db103_avaliacaotiporesposta = $this->db103_avaliacaotiporesposta ";
       $virgula = ",";
       if(trim($this->db103_avaliacaotiporesposta) == null ){ 
         $this->erro_sql = " Campo Avaliação Tipo Resposta não informado.";
         $this->erro_campo = "db103_avaliacaotiporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_avaliacaogrupopergunta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_avaliacaogrupopergunta"])){ 
       $sql  .= $virgula." db103_avaliacaogrupopergunta = $this->db103_avaliacaogrupopergunta ";
       $virgula = ",";
       if(trim($this->db103_avaliacaogrupopergunta) == null ){ 
         $this->erro_sql = " Campo Avaliação Grupo Pergunta não informado.";
         $this->erro_campo = "db103_avaliacaogrupopergunta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_descricao"])){ 
       $sql  .= $virgula." db103_descricao = '$this->db103_descricao' ";
       $virgula = ",";
       if(trim($this->db103_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "db103_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_identificador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_identificador"])){ 
       $sql  .= $virgula." db103_identificador = '$this->db103_identificador' ";
       $virgula = ",";
     }
     if(trim($this->db103_obrigatoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_obrigatoria"])){ 
       $sql  .= $virgula." db103_obrigatoria = '$this->db103_obrigatoria' ";
       $virgula = ",";
       if(trim($this->db103_obrigatoria) == null ){ 
         $this->erro_sql = " Campo Obrigatória não informado.";
         $this->erro_campo = "db103_obrigatoria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_ativo"])){ 
       $sql  .= $virgula." db103_ativo = '$this->db103_ativo' ";
       $virgula = ",";
       if(trim($this->db103_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "db103_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_ordem"])){ 
       $sql  .= $virgula." db103_ordem = $this->db103_ordem ";
       $virgula = ",";
       if(trim($this->db103_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem não informado.";
         $this->erro_campo = "db103_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_tipo"])){ 
       $sql  .= $virgula." db103_tipo = $this->db103_tipo ";
       $virgula = ",";
       if(trim($this->db103_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "db103_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db103_mascara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_mascara"])){ 
       $sql  .= $virgula." db103_mascara = '$this->db103_mascara' ";
       $virgula = ",";
     }
     if(trim($this->db103_dblayoutcampo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_dblayoutcampo"])){ 
        if(trim($this->db103_dblayoutcampo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db103_dblayoutcampo"])){ 
           $this->db103_dblayoutcampo = "0" ; 
        } 
       $sql  .= $virgula." db103_dblayoutcampo = $this->db103_dblayoutcampo ";
       $virgula = ",";
     }
     if(trim($this->db103_perguntaidentificadora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_perguntaidentificadora"])){ 
       $sql  .= $virgula." db103_perguntaidentificadora = '$this->db103_perguntaidentificadora' ";
       $virgula = ",";
     }
     if(trim($this->db103_camposql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_camposql"])){ 
       $sql  .= $virgula." db103_camposql = '$this->db103_camposql' ";
       $virgula = ",";
     }
     if(trim($this->db103_identificadorcampo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db103_identificadorcampo"])){ 
       $sql  .= $virgula." db103_identificadorcampo = '$this->db103_identificadorcampo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db103_sequencial!=null){
       $sql .= " db103_sequencial = $this->db103_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db103_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16915,'$this->db103_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_sequencial"]) || $this->db103_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2983,16915,'".AddSlashes(pg_result($resaco,$conresaco,'db103_sequencial'))."','$this->db103_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_avaliacaotiporesposta"]) || $this->db103_avaliacaotiporesposta != "")
             $resac = db_query("insert into db_acount values($acount,2983,17046,'".AddSlashes(pg_result($resaco,$conresaco,'db103_avaliacaotiporesposta'))."','$this->db103_avaliacaotiporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_avaliacaogrupopergunta"]) || $this->db103_avaliacaogrupopergunta != "")
             $resac = db_query("insert into db_acount values($acount,2983,16916,'".AddSlashes(pg_result($resaco,$conresaco,'db103_avaliacaogrupopergunta'))."','$this->db103_avaliacaogrupopergunta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_descricao"]) || $this->db103_descricao != "")
             $resac = db_query("insert into db_acount values($acount,2983,16917,'".AddSlashes(pg_result($resaco,$conresaco,'db103_descricao'))."','$this->db103_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_identificador"]) || $this->db103_identificador != "")
             $resac = db_query("insert into db_acount values($acount,2983,19378,'".AddSlashes(pg_result($resaco,$conresaco,'db103_identificador'))."','$this->db103_identificador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_obrigatoria"]) || $this->db103_obrigatoria != "")
             $resac = db_query("insert into db_acount values($acount,2983,16918,'".AddSlashes(pg_result($resaco,$conresaco,'db103_obrigatoria'))."','$this->db103_obrigatoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_ativo"]) || $this->db103_ativo != "")
             $resac = db_query("insert into db_acount values($acount,2983,16919,'".AddSlashes(pg_result($resaco,$conresaco,'db103_ativo'))."','$this->db103_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_ordem"]) || $this->db103_ordem != "")
             $resac = db_query("insert into db_acount values($acount,2983,17023,'".AddSlashes(pg_result($resaco,$conresaco,'db103_ordem'))."','$this->db103_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_tipo"]) || $this->db103_tipo != "")
             $resac = db_query("insert into db_acount values($acount,2983,21839,'".AddSlashes(pg_result($resaco,$conresaco,'db103_tipo'))."','$this->db103_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_mascara"]) || $this->db103_mascara != "")
             $resac = db_query("insert into db_acount values($acount,2983,21840,'".AddSlashes(pg_result($resaco,$conresaco,'db103_mascara'))."','$this->db103_mascara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_dblayoutcampo"]) || $this->db103_dblayoutcampo != "")
             $resac = db_query("insert into db_acount values($acount,2983,1009307,'".AddSlashes(pg_result($resaco,$conresaco,'db103_dblayoutcampo'))."','$this->db103_dblayoutcampo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_perguntaidentificadora"]) || $this->db103_perguntaidentificadora != "")
             $resac = db_query("insert into db_acount values($acount,2983,1009305,'".AddSlashes(pg_result($resaco,$conresaco,'db103_perguntaidentificadora'))."','$this->db103_perguntaidentificadora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_camposql"]) || $this->db103_camposql != "")
             $resac = db_query("insert into db_acount values($acount,2983,1009304,'".AddSlashes(pg_result($resaco,$conresaco,'db103_camposql'))."','$this->db103_camposql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db103_identificadorcampo"]) || $this->db103_identificadorcampo != "")
             $resac = db_query("insert into db_acount values($acount,2983,1009512,'".AddSlashes(pg_result($resaco,$conresaco,'db103_identificadorcampo'))."','$this->db103_identificadorcampo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Pergunta não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db103_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Pergunta não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db103_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db103_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16915,'$db103_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2983,16915,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,17046,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_avaliacaotiporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,16916,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_avaliacaogrupopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,16917,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,19378,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,16918,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_obrigatoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,16919,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,17023,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,21839,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,21840,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_mascara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,1009307,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_dblayoutcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,1009305,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_perguntaidentificadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,1009304,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_camposql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2983,1009512,'','".AddSlashes(pg_result($resaco,$iresaco,'db103_identificadorcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaopergunta
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db103_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db103_sequencial = $db103_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Pergunta não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db103_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Pergunta não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$db103_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaopergunta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($db103_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaopergunta ";
     $sql .= "      inner join avaliacaotiporesposta  on  avaliacaotiporesposta.db105_sequencial = avaliacaopergunta.db103_avaliacaotiporesposta";
     $sql .= "      inner join avaliacaogrupopergunta  on  avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta";
     $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaogrupopergunta.db102_avaliacao";
     $sql .= "      left join  avaliacaoperguntadb_formulas on  avaliacaopergunta.db103_sequencial = avaliacaoperguntadb_formulas.eso01_avaliacaopergunta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db103_sequencial)) {
         $sql2 .= " where avaliacaopergunta.db103_sequencial = $db103_sequencial ";
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
   public function sql_query_file ($db103_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaopergunta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db103_sequencial)){
         $sql2 .= " where avaliacaopergunta.db103_sequencial = $db103_sequencial ";
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

  public function sql_query_busca_formulas ($db103_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "", $sTipoJoin = 'inner') {

    $sql  = "select {$campos} ";
    $sql .= "  from avaliacaopergunta ";
    $sql .= "      {$sTipoJoin} join avaliacaoperguntadb_formulas  on  avaliacaoperguntadb_formulas.eso01_avaliacaopergunta = avaliacaopergunta.db103_sequencial";
    $sql .= "      {$sTipoJoin} join db_formulas  on  db_formulas.db148_sequencial = avaliacaoperguntadb_formulas.eso01_db_formulas";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($db103_sequencial)){
        $sql2 .= " where avaliacaopergunta.db103_sequencial = $db103_sequencial ";
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
 public function sql_query_busca_layout ($db103_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from avaliacaopergunta ";
    $sql .= "      inner join db_layoutcampos on db103_dblayoutcampo = db52_codigo";
    $sql .= "      inner join db_layoutlinha  on db52_layoutlinha    = db51_codigo";
    $sql .= "      inner join db_layouttxt    on db51_layouttxt      = db50_codigo";
    $sql .= "      inner join avaliacaotiporesposta        on  avaliacaotiporesposta.db105_sequencial = avaliacaopergunta.db103_avaliacaotiporesposta";
    $sql .= "      inner join avaliacaogrupopergunta       on  avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta";
    $sql .= "      inner join avaliacao                    on  avaliacao.db101_sequencial = avaliacaogrupopergunta.db102_avaliacao";

    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($db103_sequencial)){
        $sql2 .= " where avaliacaopergunta.db103_sequencial = $db103_sequencial ";
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
