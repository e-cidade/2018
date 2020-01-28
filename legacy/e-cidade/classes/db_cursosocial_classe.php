<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: social
//CLASSE DA ENTIDADE cursosocial
class cl_cursosocial { 
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
   var $as19_sequencial = 0; 
   var $as19_nome = null; 
   var $as19_detalhamento = null; 
   var $as19_tabcurritipo = 0; 
   var $as19_inicio_dia = null; 
   var $as19_inicio_mes = null; 
   var $as19_inicio_ano = null; 
   var $as19_inicio = null; 
   var $as19_fim_dia = null; 
   var $as19_fim_mes = null; 
   var $as19_fim_ano = null; 
   var $as19_fim = null; 
   var $as19_horaaulasdia = 0; 
   var $as19_ministrante = 0; 
   var $as19_responsavel = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as19_sequencial = int4 = Código 
                 as19_nome = varchar(70) = Nome do Curso 
                 as19_detalhamento = text = Resumo 
                 as19_tabcurritipo = int4 = Tipo de curso 
                 as19_inicio = date = Início 
                 as19_fim = date = Encerramento 
                 as19_horaaulasdia = numeric(10) = Horas por dia 
                 as19_ministrante = int4 = Ministrante 
                 as19_responsavel = int4 = Responsável 
                 ";
   //funcao construtor da classe 
   function cl_cursosocial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cursosocial"); 
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
       $this->as19_sequencial = ($this->as19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_sequencial"]:$this->as19_sequencial);
       $this->as19_nome = ($this->as19_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_nome"]:$this->as19_nome);
       $this->as19_detalhamento = ($this->as19_detalhamento == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_detalhamento"]:$this->as19_detalhamento);
       $this->as19_tabcurritipo = ($this->as19_tabcurritipo == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_tabcurritipo"]:$this->as19_tabcurritipo);
       if($this->as19_inicio == ""){
         $this->as19_inicio_dia = ($this->as19_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_inicio_dia"]:$this->as19_inicio_dia);
         $this->as19_inicio_mes = ($this->as19_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_inicio_mes"]:$this->as19_inicio_mes);
         $this->as19_inicio_ano = ($this->as19_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_inicio_ano"]:$this->as19_inicio_ano);
         if($this->as19_inicio_dia != ""){
            $this->as19_inicio = $this->as19_inicio_ano."-".$this->as19_inicio_mes."-".$this->as19_inicio_dia;
         }
       }
       if($this->as19_fim == ""){
         $this->as19_fim_dia = ($this->as19_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_fim_dia"]:$this->as19_fim_dia);
         $this->as19_fim_mes = ($this->as19_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_fim_mes"]:$this->as19_fim_mes);
         $this->as19_fim_ano = ($this->as19_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_fim_ano"]:$this->as19_fim_ano);
         if($this->as19_fim_dia != ""){
            $this->as19_fim = $this->as19_fim_ano."-".$this->as19_fim_mes."-".$this->as19_fim_dia;
         }
       }
       $this->as19_horaaulasdia = ($this->as19_horaaulasdia == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_horaaulasdia"]:$this->as19_horaaulasdia);
       $this->as19_ministrante = ($this->as19_ministrante == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_ministrante"]:$this->as19_ministrante);
       $this->as19_responsavel = ($this->as19_responsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_responsavel"]:$this->as19_responsavel);
     }else{
       $this->as19_sequencial = ($this->as19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as19_sequencial"]:$this->as19_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as19_sequencial){ 
      $this->atualizacampos();
     if($this->as19_nome == null ){ 
       $this->erro_sql = " Campo Nome do Curso nao Informado.";
       $this->erro_campo = "as19_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as19_detalhamento == null ){ 
       $this->erro_sql = " Campo Resumo nao Informado.";
       $this->erro_campo = "as19_detalhamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as19_tabcurritipo == null ){ 
       $this->erro_sql = " Campo Tipo de curso nao Informado.";
       $this->erro_campo = "as19_tabcurritipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as19_inicio == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "as19_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as19_fim == null ){ 
       $this->erro_sql = " Campo Encerramento nao Informado.";
       $this->erro_campo = "as19_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as19_horaaulasdia == null ){ 
       $this->erro_sql = " Campo Horas por dia nao Informado.";
       $this->erro_campo = "as19_horaaulasdia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as19_ministrante == null ){ 
       $this->erro_sql = " Campo Ministrante nao Informado.";
       $this->erro_campo = "as19_ministrante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as19_responsavel == null ){ 
       $this->erro_sql = " Campo Responsável nao Informado.";
       $this->erro_campo = "as19_responsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as19_sequencial == "" || $as19_sequencial == null ){
       $result = db_query("select nextval('cursosocial_as19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursosocial_as19_sequencial_seq do campo: as19_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cursosocial_as19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as19_sequencial)){
         $this->erro_sql = " Campo as19_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as19_sequencial = $as19_sequencial; 
       }
     }
     if(($this->as19_sequencial == null) || ($this->as19_sequencial == "") ){ 
       $this->erro_sql = " Campo as19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cursosocial(
                                       as19_sequencial 
                                      ,as19_nome 
                                      ,as19_detalhamento 
                                      ,as19_tabcurritipo 
                                      ,as19_inicio 
                                      ,as19_fim 
                                      ,as19_horaaulasdia 
                                      ,as19_ministrante 
                                      ,as19_responsavel 
                       )
                values (
                                $this->as19_sequencial 
                               ,'$this->as19_nome' 
                               ,'$this->as19_detalhamento' 
                               ,$this->as19_tabcurritipo 
                               ,".($this->as19_inicio == "null" || $this->as19_inicio == ""?"null":"'".$this->as19_inicio."'")." 
                               ,".($this->as19_fim == "null" || $this->as19_fim == ""?"null":"'".$this->as19_fim."'")." 
                               ,$this->as19_horaaulasdia 
                               ,$this->as19_ministrante 
                               ,$this->as19_responsavel 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Curso Social ($this->as19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Curso Social já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Curso Social ($this->as19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as19_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as19_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19961,'$this->as19_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3578,19961,'','".AddSlashes(pg_result($resaco,0,'as19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19962,'','".AddSlashes(pg_result($resaco,0,'as19_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19963,'','".AddSlashes(pg_result($resaco,0,'as19_detalhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19964,'','".AddSlashes(pg_result($resaco,0,'as19_tabcurritipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19965,'','".AddSlashes(pg_result($resaco,0,'as19_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19966,'','".AddSlashes(pg_result($resaco,0,'as19_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19967,'','".AddSlashes(pg_result($resaco,0,'as19_horaaulasdia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19968,'','".AddSlashes(pg_result($resaco,0,'as19_ministrante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3578,19969,'','".AddSlashes(pg_result($resaco,0,'as19_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as19_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cursosocial set ";
     $virgula = "";
     if(trim($this->as19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_sequencial"])){ 
       $sql  .= $virgula." as19_sequencial = $this->as19_sequencial ";
       $virgula = ",";
       if(trim($this->as19_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as19_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_nome"])){ 
       $sql  .= $virgula." as19_nome = '$this->as19_nome' ";
       $virgula = ",";
       if(trim($this->as19_nome) == null ){ 
         $this->erro_sql = " Campo Nome do Curso nao Informado.";
         $this->erro_campo = "as19_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as19_detalhamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_detalhamento"])){ 
       $sql  .= $virgula." as19_detalhamento = '$this->as19_detalhamento' ";
       $virgula = ",";
       if(trim($this->as19_detalhamento) == null ){ 
         $this->erro_sql = " Campo Resumo nao Informado.";
         $this->erro_campo = "as19_detalhamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as19_tabcurritipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_tabcurritipo"])){ 
       $sql  .= $virgula." as19_tabcurritipo = $this->as19_tabcurritipo ";
       $virgula = ",";
       if(trim($this->as19_tabcurritipo) == null ){ 
         $this->erro_sql = " Campo Tipo de curso nao Informado.";
         $this->erro_campo = "as19_tabcurritipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as19_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as19_inicio_dia"] !="") ){ 
       $sql  .= $virgula." as19_inicio = '$this->as19_inicio' ";
       $virgula = ",";
       if(trim($this->as19_inicio) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "as19_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as19_inicio_dia"])){ 
         $sql  .= $virgula." as19_inicio = null ";
         $virgula = ",";
         if(trim($this->as19_inicio) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "as19_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->as19_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as19_fim_dia"] !="") ){ 
       $sql  .= $virgula." as19_fim = '$this->as19_fim' ";
       $virgula = ",";
       if(trim($this->as19_fim) == null ){ 
         $this->erro_sql = " Campo Encerramento nao Informado.";
         $this->erro_campo = "as19_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as19_fim_dia"])){ 
         $sql  .= $virgula." as19_fim = null ";
         $virgula = ",";
         if(trim($this->as19_fim) == null ){ 
           $this->erro_sql = " Campo Encerramento nao Informado.";
           $this->erro_campo = "as19_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->as19_horaaulasdia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_horaaulasdia"])){ 
       $sql  .= $virgula." as19_horaaulasdia = $this->as19_horaaulasdia ";
       $virgula = ",";
       if(trim($this->as19_horaaulasdia) == null ){ 
         $this->erro_sql = " Campo Horas por dia nao Informado.";
         $this->erro_campo = "as19_horaaulasdia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as19_ministrante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_ministrante"])){ 
       $sql  .= $virgula." as19_ministrante = $this->as19_ministrante ";
       $virgula = ",";
       if(trim($this->as19_ministrante) == null ){ 
         $this->erro_sql = " Campo Ministrante nao Informado.";
         $this->erro_campo = "as19_ministrante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as19_responsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as19_responsavel"])){ 
       $sql  .= $virgula." as19_responsavel = $this->as19_responsavel ";
       $virgula = ",";
       if(trim($this->as19_responsavel) == null ){ 
         $this->erro_sql = " Campo Responsável nao Informado.";
         $this->erro_campo = "as19_responsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as19_sequencial!=null){
       $sql .= " as19_sequencial = $this->as19_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as19_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19961,'$this->as19_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_sequencial"]) || $this->as19_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3578,19961,'".AddSlashes(pg_result($resaco,$conresaco,'as19_sequencial'))."','$this->as19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_nome"]) || $this->as19_nome != "")
             $resac = db_query("insert into db_acount values($acount,3578,19962,'".AddSlashes(pg_result($resaco,$conresaco,'as19_nome'))."','$this->as19_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_detalhamento"]) || $this->as19_detalhamento != "")
             $resac = db_query("insert into db_acount values($acount,3578,19963,'".AddSlashes(pg_result($resaco,$conresaco,'as19_detalhamento'))."','$this->as19_detalhamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_tabcurritipo"]) || $this->as19_tabcurritipo != "")
             $resac = db_query("insert into db_acount values($acount,3578,19964,'".AddSlashes(pg_result($resaco,$conresaco,'as19_tabcurritipo'))."','$this->as19_tabcurritipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_inicio"]) || $this->as19_inicio != "")
             $resac = db_query("insert into db_acount values($acount,3578,19965,'".AddSlashes(pg_result($resaco,$conresaco,'as19_inicio'))."','$this->as19_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_fim"]) || $this->as19_fim != "")
             $resac = db_query("insert into db_acount values($acount,3578,19966,'".AddSlashes(pg_result($resaco,$conresaco,'as19_fim'))."','$this->as19_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_horaaulasdia"]) || $this->as19_horaaulasdia != "")
             $resac = db_query("insert into db_acount values($acount,3578,19967,'".AddSlashes(pg_result($resaco,$conresaco,'as19_horaaulasdia'))."','$this->as19_horaaulasdia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_ministrante"]) || $this->as19_ministrante != "")
             $resac = db_query("insert into db_acount values($acount,3578,19968,'".AddSlashes(pg_result($resaco,$conresaco,'as19_ministrante'))."','$this->as19_ministrante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as19_responsavel"]) || $this->as19_responsavel != "")
             $resac = db_query("insert into db_acount values($acount,3578,19969,'".AddSlashes(pg_result($resaco,$conresaco,'as19_responsavel'))."','$this->as19_responsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Curso Social nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Curso Social nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as19_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as19_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19961,'$as19_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3578,19961,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19962,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19963,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_detalhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19964,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_tabcurritipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19965,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19966,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19967,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_horaaulasdia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19968,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_ministrante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3578,19969,'','".AddSlashes(pg_result($resaco,$iresaco,'as19_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cursosocial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as19_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as19_sequencial = $as19_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Curso Social nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Curso Social nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cursosocial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursosocial ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cursosocial.as19_ministrante";
     $sql .= "      inner join tabcurritipo  on  tabcurritipo.h02_codigo = cursosocial.as19_tabcurritipo";
     $sql2 = "";
     if($dbwhere==""){
       if($as19_sequencial!=null ){
         $sql2 .= " where cursosocial.as19_sequencial = $as19_sequencial "; 
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
   function sql_query_file ( $as19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursosocial ";
     $sql2 = "";
     if($dbwhere==""){
       if($as19_sequencial!=null ){
         $sql2 .= " where cursosocial.as19_sequencial = $as19_sequencial "; 
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
  
  function sql_query_completo ($as19_nome = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*") {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cursosocial ";
    $sql .= "      inner join tabcurritipo on tabcurritipo.h02_codigo = cursosocial.as19_tabcurritipo";
    $sql .= "      inner join cgm m on m.z01_numcgm                   = cursosocial.as19_ministrante";
    $sql .= "      inner join cgm r on r.z01_numcgm                   = cursosocial.as19_responsavel";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($as19_nome != null) {
        $sql2 .= " where cursosocial.as19_nome = '$as19_nome' ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>