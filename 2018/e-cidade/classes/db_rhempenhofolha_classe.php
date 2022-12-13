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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhempenhofolha
class cl_rhempenhofolha { 
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
   var $rh72_sequencial = 0; 
   var $rh72_coddot = 0; 
   var $rh72_codele = 0; 
   var $rh72_unidade = 0; 
   var $rh72_orgao = 0; 
   var $rh72_projativ = 0; 
   var $rh72_anousu = 0; 
   var $rh72_recurso = 0; 
   var $rh72_mesusu = 0; 
   var $rh72_siglaarq = null; 
   var $rh72_tipoempenho = 0; 
   var $rh72_tabprev = 0; 
   var $rh72_seqcompl = 0; 
   var $rh72_concarpeculiar = null; 
   var $rh72_funcao = 0; 
   var $rh72_subfuncao = 0; 
   var $rh72_programa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh72_sequencial = int4 = Sequencial 
                 rh72_coddot = int4 = Dotação 
                 rh72_codele = int4 = Elemento 
                 rh72_unidade = int4 = Unidade 
                 rh72_orgao = int4 = Órgão 
                 rh72_projativ = int4 = Projetos / Atividades 
                 rh72_anousu = int4 = Exercício 
                 rh72_recurso = int4 = Recurso 
                 rh72_mesusu = int4 = Mês 
                 rh72_siglaarq = char(3) = Sigla 
                 rh72_tipoempenho = int4 = Tipo Empenho 
                 rh72_tabprev = int4 = Tabela de Previdência 
                 rh72_seqcompl = int4 = Sequencia de Folha Complementar 
                 rh72_concarpeculiar = varchar(100) = Caracteristica Peculiar 
                 rh72_funcao = int4 = Função 
                 rh72_subfuncao = int4 = Subfunção 
                 rh72_programa = int4 = Programa 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolha"); 
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
       $this->rh72_sequencial = ($this->rh72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_sequencial"]:$this->rh72_sequencial);
       $this->rh72_coddot = ($this->rh72_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_coddot"]:$this->rh72_coddot);
       $this->rh72_codele = ($this->rh72_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_codele"]:$this->rh72_codele);
       $this->rh72_unidade = ($this->rh72_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_unidade"]:$this->rh72_unidade);
       $this->rh72_orgao = ($this->rh72_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_orgao"]:$this->rh72_orgao);
       $this->rh72_projativ = ($this->rh72_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_projativ"]:$this->rh72_projativ);
       $this->rh72_anousu = ($this->rh72_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_anousu"]:$this->rh72_anousu);
       $this->rh72_recurso = ($this->rh72_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_recurso"]:$this->rh72_recurso);
       $this->rh72_mesusu = ($this->rh72_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_mesusu"]:$this->rh72_mesusu);
       $this->rh72_siglaarq = ($this->rh72_siglaarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_siglaarq"]:$this->rh72_siglaarq);
       $this->rh72_tipoempenho = ($this->rh72_tipoempenho == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_tipoempenho"]:$this->rh72_tipoempenho);
       $this->rh72_tabprev = ($this->rh72_tabprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_tabprev"]:$this->rh72_tabprev);
       $this->rh72_seqcompl = ($this->rh72_seqcompl == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_seqcompl"]:$this->rh72_seqcompl);
       $this->rh72_concarpeculiar = ($this->rh72_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_concarpeculiar"]:$this->rh72_concarpeculiar);
       $this->rh72_funcao = ($this->rh72_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_funcao"]:$this->rh72_funcao);
       $this->rh72_subfuncao = ($this->rh72_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_subfuncao"]:$this->rh72_subfuncao);
       $this->rh72_programa = ($this->rh72_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_programa"]:$this->rh72_programa);
     }else{
       $this->rh72_sequencial = ($this->rh72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh72_sequencial"]:$this->rh72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh72_sequencial){ 
      $this->atualizacampos();
     if($this->rh72_coddot == null ){ 
       $this->rh72_coddot = "0";
     }
     if($this->rh72_codele == null ){ 
       $this->erro_sql = " Campo Elemento nao Informado.";
       $this->erro_campo = "rh72_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "rh72_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_orgao == null ){ 
       $this->erro_sql = " Campo Órgão nao Informado.";
       $this->erro_campo = "rh72_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_projativ == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
       $this->erro_campo = "rh72_projativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "rh72_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_recurso == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "rh72_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh72_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_siglaarq == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "rh72_siglaarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_tipoempenho == null ){ 
       $this->erro_sql = " Campo Tipo Empenho nao Informado.";
       $this->erro_campo = "rh72_tipoempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_tabprev == null ){ 
       $this->erro_sql = " Campo Tabela de Previdência nao Informado.";
       $this->erro_campo = "rh72_tabprev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_seqcompl == null ){ 
       $this->erro_sql = " Campo Sequencia de Folha Complementar nao Informado.";
       $this->erro_campo = "rh72_seqcompl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_concarpeculiar == null ){ 
       $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
       $this->erro_campo = "rh72_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh72_funcao == null ){ 
       $this->rh72_funcao = "null";
     }
     if($this->rh72_subfuncao == null ){ 
       $this->rh72_subfuncao = "null";
     }
     if($this->rh72_programa == null ){ 
       $this->rh72_programa = "null";
     }
     if($rh72_sequencial == "" || $rh72_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolha_rh72_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolha_rh72_sequencial_seq do campo: rh72_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh72_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolha_rh72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh72_sequencial)){
         $this->erro_sql = " Campo rh72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh72_sequencial = $rh72_sequencial; 
       }
     }
     if(($this->rh72_sequencial == null) || ($this->rh72_sequencial == "") ){ 
       $this->erro_sql = " Campo rh72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolha(
                                       rh72_sequencial 
                                      ,rh72_coddot 
                                      ,rh72_codele 
                                      ,rh72_unidade 
                                      ,rh72_orgao 
                                      ,rh72_projativ 
                                      ,rh72_anousu 
                                      ,rh72_recurso 
                                      ,rh72_mesusu 
                                      ,rh72_siglaarq 
                                      ,rh72_tipoempenho 
                                      ,rh72_tabprev 
                                      ,rh72_seqcompl 
                                      ,rh72_concarpeculiar 
                                      ,rh72_funcao 
                                      ,rh72_subfuncao 
                                      ,rh72_programa 
                       )
                values (
                                $this->rh72_sequencial 
                               ,$this->rh72_coddot 
                               ,$this->rh72_codele 
                               ,$this->rh72_unidade 
                               ,$this->rh72_orgao 
                               ,$this->rh72_projativ 
                               ,$this->rh72_anousu 
                               ,$this->rh72_recurso 
                               ,$this->rh72_mesusu 
                               ,'$this->rh72_siglaarq' 
                               ,$this->rh72_tipoempenho 
                               ,$this->rh72_tabprev 
                               ,$this->rh72_seqcompl 
                               ,'$this->rh72_concarpeculiar' 
                               ,$this->rh72_funcao 
                               ,$this->rh72_subfuncao 
                               ,$this->rh72_programa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhempenhofolha ($this->rh72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhempenhofolha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhempenhofolha ($this->rh72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14236,'$this->rh72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2506,14236,'','".AddSlashes(pg_result($resaco,0,'rh72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14237,'','".AddSlashes(pg_result($resaco,0,'rh72_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14238,'','".AddSlashes(pg_result($resaco,0,'rh72_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14239,'','".AddSlashes(pg_result($resaco,0,'rh72_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14240,'','".AddSlashes(pg_result($resaco,0,'rh72_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14241,'','".AddSlashes(pg_result($resaco,0,'rh72_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14242,'','".AddSlashes(pg_result($resaco,0,'rh72_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14243,'','".AddSlashes(pg_result($resaco,0,'rh72_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14396,'','".AddSlashes(pg_result($resaco,0,'rh72_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14397,'','".AddSlashes(pg_result($resaco,0,'rh72_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14253,'','".AddSlashes(pg_result($resaco,0,'rh72_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14255,'','".AddSlashes(pg_result($resaco,0,'rh72_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,14256,'','".AddSlashes(pg_result($resaco,0,'rh72_seqcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,15048,'','".AddSlashes(pg_result($resaco,0,'rh72_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,19173,'','".AddSlashes(pg_result($resaco,0,'rh72_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,19174,'','".AddSlashes(pg_result($resaco,0,'rh72_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2506,19175,'','".AddSlashes(pg_result($resaco,0,'rh72_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh72_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolha set ";
     $virgula = "";
     if(trim($this->rh72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_sequencial"])){ 
       $sql  .= $virgula." rh72_sequencial = $this->rh72_sequencial ";
       $virgula = ",";
       if(trim($this->rh72_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_coddot"])){ 
        if(trim($this->rh72_coddot)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh72_coddot"])){ 
           $this->rh72_coddot = "0" ; 
        } 
       $sql  .= $virgula." rh72_coddot = $this->rh72_coddot ";
       $virgula = ",";
     }
     if(trim($this->rh72_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_codele"])){ 
       $sql  .= $virgula." rh72_codele = $this->rh72_codele ";
       $virgula = ",";
       if(trim($this->rh72_codele) == null ){ 
         $this->erro_sql = " Campo Elemento nao Informado.";
         $this->erro_campo = "rh72_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_unidade"])){ 
       $sql  .= $virgula." rh72_unidade = $this->rh72_unidade ";
       $virgula = ",";
       if(trim($this->rh72_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "rh72_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_orgao"])){ 
       $sql  .= $virgula." rh72_orgao = $this->rh72_orgao ";
       $virgula = ",";
       if(trim($this->rh72_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "rh72_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_projativ"])){ 
       $sql  .= $virgula." rh72_projativ = $this->rh72_projativ ";
       $virgula = ",";
       if(trim($this->rh72_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "rh72_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_anousu"])){ 
       $sql  .= $virgula." rh72_anousu = $this->rh72_anousu ";
       $virgula = ",";
       if(trim($this->rh72_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "rh72_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_recurso"])){ 
       $sql  .= $virgula." rh72_recurso = $this->rh72_recurso ";
       $virgula = ",";
       if(trim($this->rh72_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "rh72_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_mesusu"])){ 
       $sql  .= $virgula." rh72_mesusu = $this->rh72_mesusu ";
       $virgula = ",";
       if(trim($this->rh72_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh72_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_siglaarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_siglaarq"])){ 
       $sql  .= $virgula." rh72_siglaarq = '$this->rh72_siglaarq' ";
       $virgula = ",";
       if(trim($this->rh72_siglaarq) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "rh72_siglaarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_tipoempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_tipoempenho"])){ 
       $sql  .= $virgula." rh72_tipoempenho = $this->rh72_tipoempenho ";
       $virgula = ",";
       if(trim($this->rh72_tipoempenho) == null ){ 
         $this->erro_sql = " Campo Tipo Empenho nao Informado.";
         $this->erro_campo = "rh72_tipoempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_tabprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_tabprev"])){ 
       $sql  .= $virgula." rh72_tabprev = $this->rh72_tabprev ";
       $virgula = ",";
       if(trim($this->rh72_tabprev) == null ){ 
         $this->erro_sql = " Campo Tabela de Previdência nao Informado.";
         $this->erro_campo = "rh72_tabprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_seqcompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_seqcompl"])){ 
       $sql  .= $virgula." rh72_seqcompl = $this->rh72_seqcompl ";
       $virgula = ",";
       if(trim($this->rh72_seqcompl) == null ){ 
         $this->erro_sql = " Campo Sequencia de Folha Complementar nao Informado.";
         $this->erro_campo = "rh72_seqcompl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_concarpeculiar"])){ 
       $sql  .= $virgula." rh72_concarpeculiar = '$this->rh72_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->rh72_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
         $this->erro_campo = "rh72_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh72_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_funcao"])){ 
        if(trim($this->rh72_funcao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh72_funcao"])){ 
           $this->rh72_funcao = "null" ; 
        } 
       $sql  .= $virgula." rh72_funcao = $this->rh72_funcao ";
       $virgula = ",";
     }
     if(trim($this->rh72_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_subfuncao"])){ 
        if(trim($this->rh72_subfuncao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh72_subfuncao"])){ 
           $this->rh72_subfuncao = "null" ; 
        } 
       $sql  .= $virgula." rh72_subfuncao = $this->rh72_subfuncao ";
       $virgula = ",";
     }
     if(trim($this->rh72_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh72_programa"])){ 
        if(trim($this->rh72_programa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh72_programa"])){ 
           $this->rh72_programa = "null" ; 
        } 
       $sql  .= $virgula." rh72_programa = $this->rh72_programa ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh72_sequencial!=null){
       $sql .= " rh72_sequencial = $this->rh72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14236,'$this->rh72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_sequencial"]) || $this->rh72_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2506,14236,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_sequencial'))."','$this->rh72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_coddot"]) || $this->rh72_coddot != "")
           $resac = db_query("insert into db_acount values($acount,2506,14237,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_coddot'))."','$this->rh72_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_codele"]) || $this->rh72_codele != "")
           $resac = db_query("insert into db_acount values($acount,2506,14238,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_codele'))."','$this->rh72_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_unidade"]) || $this->rh72_unidade != "")
           $resac = db_query("insert into db_acount values($acount,2506,14239,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_unidade'))."','$this->rh72_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_orgao"]) || $this->rh72_orgao != "")
           $resac = db_query("insert into db_acount values($acount,2506,14240,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_orgao'))."','$this->rh72_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_projativ"]) || $this->rh72_projativ != "")
           $resac = db_query("insert into db_acount values($acount,2506,14241,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_projativ'))."','$this->rh72_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_anousu"]) || $this->rh72_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2506,14242,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_anousu'))."','$this->rh72_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_recurso"]) || $this->rh72_recurso != "")
           $resac = db_query("insert into db_acount values($acount,2506,14243,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_recurso'))."','$this->rh72_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_mesusu"]) || $this->rh72_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2506,14396,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_mesusu'))."','$this->rh72_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_siglaarq"]) || $this->rh72_siglaarq != "")
           $resac = db_query("insert into db_acount values($acount,2506,14397,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_siglaarq'))."','$this->rh72_siglaarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_tipoempenho"]) || $this->rh72_tipoempenho != "")
           $resac = db_query("insert into db_acount values($acount,2506,14253,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_tipoempenho'))."','$this->rh72_tipoempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_tabprev"]) || $this->rh72_tabprev != "")
           $resac = db_query("insert into db_acount values($acount,2506,14255,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_tabprev'))."','$this->rh72_tabprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_seqcompl"]) || $this->rh72_seqcompl != "")
           $resac = db_query("insert into db_acount values($acount,2506,14256,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_seqcompl'))."','$this->rh72_seqcompl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_concarpeculiar"]) || $this->rh72_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,2506,15048,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_concarpeculiar'))."','$this->rh72_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_funcao"]) || $this->rh72_funcao != "")
           $resac = db_query("insert into db_acount values($acount,2506,19173,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_funcao'))."','$this->rh72_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_subfuncao"]) || $this->rh72_subfuncao != "")
           $resac = db_query("insert into db_acount values($acount,2506,19174,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_subfuncao'))."','$this->rh72_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh72_programa"]) || $this->rh72_programa != "")
           $resac = db_query("insert into db_acount values($acount,2506,19175,'".AddSlashes(pg_result($resaco,$conresaco,'rh72_programa'))."','$this->rh72_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh72_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh72_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14236,'$rh72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2506,14236,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14237,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14238,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14239,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14240,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14241,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14242,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14243,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14396,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14397,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14253,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14255,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,14256,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_seqcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,15048,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,19173,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,19174,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2506,19175,'','".AddSlashes(pg_result($resaco,$iresaco,'rh72_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempenhofolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh72_sequencial = $rh72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolha ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhempenhofolha.rh72_recurso";
     $sql .= "      left  join orcfuncao  on  orcfuncao.o52_funcao = rhempenhofolha.rh72_funcao";
     $sql .= "      left  join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = rhempenhofolha.rh72_subfuncao";
     $sql .= "      left  join orcprograma  on  orcprograma.o54_anousu = rhempenhofolha.rh72_anousu and  orcprograma.o54_programa = rhempenhofolha.rh72_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhempenhofolha.rh72_codele and  orcelemento.o56_anousu = rhempenhofolha.rh72_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = rhempenhofolha.rh72_anousu and  orcprojativ.o55_projativ = rhempenhofolha.rh72_projativ";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = rhempenhofolha.rh72_anousu and  orcunidade.o41_orgao = rhempenhofolha.rh72_orgao and  orcunidade.o41_unidade = rhempenhofolha.rh72_unidade";
     $sql .= "      left  join orcdotacao  on  orcdotacao.o58_anousu = rhempenhofolha.rh72_coddot and  orcdotacao.o58_coddot = rhempenhofolha.rh72_anousu";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = rhempenhofolha.rh72_concarpeculiar";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql .= "      inner join db_config  as a on   a.codigo = orcunidade.o41_instit";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join db_config  as b on   b.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  as c on   c.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  as d on   d.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  as d on   d.o54_anousu = orcdotacao.o58_anousu and   d.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  as d on   d.o56_codele = orcdotacao.o58_codele and   d.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  as d on   d.o55_anousu = orcdotacao.o58_anousu and   d.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcdotacao.o58_anousu and   d.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  as d on   d.o41_anousu = orcdotacao.o58_anousu and   d.o41_orgao = orcdotacao.o58_orgao and   d.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join concarpeculiar  as d on   d.c58_sequencial = orcdotacao.o58_concarpeculiar";
     $sql .= "      inner join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = orcdotacao.o58_localizadorgastos";
     $sql .= "      inner join db_estruturavalor  as d on   d.db121_sequencial = concarpeculiar.c58_db_estruturavalor";
     $sql .= "      inner join concarpeculiarclassificacao  on  concarpeculiarclassificacao.c09_sequencial = concarpeculiar.c58_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($rh72_sequencial!=null ){
         $sql2 .= " where rhempenhofolha.rh72_sequencial = $rh72_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $rh72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolha ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh72_sequencial!=null ){
         $sql2 .= " where rhempenhofolha.rh72_sequencial = $rh72_sequencial "; 
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
   function sql_query_rubricas( $rh72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolha ";
     $sql .= "      left  join rhempenhofolharhemprubrica on rhempenhofolharhemprubrica.rh81_rhempenhofolha = rhempenhofolha.rh72_sequencial                        ";
     $sql .= "      left  join rhempenhofolharubrica      on rhempenhofolharubrica.rh73_sequencial          = rhempenhofolharhemprubrica.rh81_rhempenhofolharubrica ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($rh72_sequencial!=null ){
         $sql2 .= " where rhempenhofolha.rh72_sequencial = $rh72_sequencial "; 
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

  /**
   * Retorna os empenhos da folha, que não foram cancelados.
   * 
   * @param  integer  $iAnoUsu       
   * @param  integer  $iMesUsu       
   * @param  String   $sSiglaArquivo 
   * @param  String   $sCampos       
   * @param  String   $seqCompl      
   * @return String   $sSql          
   */
  function sql_query_empenhado($iAnoUsu, $iMesUsu, $sSiglaArquivo = "*", $sCampos, $seqCompl = false){

    $sSql  = "select {$sCampos} from rhempenhofolha";
    $sSql .= " inner join rhempenhofolhaempenho";
    $sSql .= "         on rh76_rhempenhofolha = rh72_sequencial";
    $sSql .= " where rh72_anousu   = {$iAnoUsu} ";
    $sSql .= "   and rh72_mesusu   = {$iMesUsu} ";
    $sSql .= "   and rh72_siglaarq = '{$sSiglaArquivo}'";

    if ($seqCompl){
      $sSql .= "and rh72_seqcompl = {$seqCompl} ";  
    }

    return $sSql;
  }
}
?>