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

//MODULO: escola
//CLASSE DA ENTIDADE procavaliacao
class cl_procavaliacao { 
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
   var $ed41_i_codigo = 0; 
   var $ed41_i_procedimento = 0; 
   var $ed41_i_periodoavaliacao = 0; 
   var $ed41_i_formaavaliacao = 0; 
   var $ed41_i_procavalvinc = 0; 
   var $ed41_i_procresultvinc = 0; 
   var $ed41_c_boletim = null; 
   var $ed41_i_sequencia = 0; 
   var $ed41_numerodisciplinasrecuperacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed41_i_codigo = int8 = C�digo 
                 ed41_i_procedimento = int8 = Procedimento de Avalia��o 
                 ed41_i_periodoavaliacao = int8 = Per�odo de Avalia��o 
                 ed41_i_formaavaliacao = int8 = Forma de Avalia��o 
                 ed41_i_procavalvinc = int4 = Per�odo de Avalia��o Vinculado 
                 ed41_i_procresultvinc = int4 = Resultado Vinculado 
                 ed41_c_boletim = char(1) = Aparece no Boletim 
                 ed41_i_sequencia = int4 = Ordena��o 
                 ed41_numerodisciplinasrecuperacao = int4 = N�mero de Disciplinas Reprova��o 
                 ";
   //funcao construtor da classe 
   function cl_procavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procavaliacao"); 
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
       $this->ed41_i_codigo = ($this->ed41_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_codigo"]:$this->ed41_i_codigo);
       $this->ed41_i_procedimento = ($this->ed41_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_procedimento"]:$this->ed41_i_procedimento);
       $this->ed41_i_periodoavaliacao = ($this->ed41_i_periodoavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_periodoavaliacao"]:$this->ed41_i_periodoavaliacao);
       $this->ed41_i_formaavaliacao = ($this->ed41_i_formaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_formaavaliacao"]:$this->ed41_i_formaavaliacao);
       $this->ed41_i_procavalvinc = ($this->ed41_i_procavalvinc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_procavalvinc"]:$this->ed41_i_procavalvinc);
       $this->ed41_i_procresultvinc = ($this->ed41_i_procresultvinc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_procresultvinc"]:$this->ed41_i_procresultvinc);
       $this->ed41_c_boletim = ($this->ed41_c_boletim == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_c_boletim"]:$this->ed41_c_boletim);
       $this->ed41_i_sequencia = ($this->ed41_i_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_sequencia"]:$this->ed41_i_sequencia);
       $this->ed41_numerodisciplinasrecuperacao = ($this->ed41_numerodisciplinasrecuperacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_numerodisciplinasrecuperacao"]:$this->ed41_numerodisciplinasrecuperacao);
     }else{
       $this->ed41_i_codigo = ($this->ed41_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed41_i_codigo"]:$this->ed41_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed41_i_codigo){ 
      $this->atualizacampos();
     if($this->ed41_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento de Avalia��o n�o informado.";
       $this->erro_campo = "ed41_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed41_i_periodoavaliacao == null ){ 
       $this->erro_sql = " Campo Per�odo de Avalia��o n�o informado.";
       $this->erro_campo = "ed41_i_periodoavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed41_i_formaavaliacao == null ){ 
       $this->erro_sql = " Campo Forma de Avalia��o n�o informado.";
       $this->erro_campo = "ed41_i_formaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed41_i_procavalvinc == null ){ 
       $this->ed41_i_procavalvinc = "0";
     }
     if($this->ed41_i_procresultvinc == null ){ 
       $this->ed41_i_procresultvinc = "0";
     }
     if($this->ed41_c_boletim == null ){ 
       $this->erro_sql = " Campo Aparece no Boletim n�o informado.";
       $this->erro_campo = "ed41_c_boletim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed41_i_sequencia == null ){ 
       $this->erro_sql = " Campo Ordena��o n�o informado.";
       $this->erro_campo = "ed41_i_sequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed41_numerodisciplinasrecuperacao == null ){ 
       $this->ed41_numerodisciplinasrecuperacao = "null";
     }
     if($ed41_i_codigo == "" || $ed41_i_codigo == null ){
       $result = db_query("select nextval('procavaliacao_ed41_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procavaliacao_ed41_i_codigo_seq do campo: ed41_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed41_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procavaliacao_ed41_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed41_i_codigo)){
         $this->erro_sql = " Campo ed41_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed41_i_codigo = $ed41_i_codigo; 
       }
     }
     if(($this->ed41_i_codigo == null) || ($this->ed41_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed41_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procavaliacao(
                                       ed41_i_codigo 
                                      ,ed41_i_procedimento 
                                      ,ed41_i_periodoavaliacao 
                                      ,ed41_i_formaavaliacao 
                                      ,ed41_i_procavalvinc 
                                      ,ed41_i_procresultvinc 
                                      ,ed41_c_boletim 
                                      ,ed41_i_sequencia 
                                      ,ed41_numerodisciplinasrecuperacao 
                       )
                values (
                                $this->ed41_i_codigo 
                               ,$this->ed41_i_procedimento 
                               ,$this->ed41_i_periodoavaliacao 
                               ,$this->ed41_i_formaavaliacao 
                               ,$this->ed41_i_procavalvinc 
                               ,$this->ed41_i_procresultvinc 
                               ,'$this->ed41_c_boletim' 
                               ,$this->ed41_i_sequencia 
                               ,$this->ed41_numerodisciplinasrecuperacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avalia��es do Procedimento ($this->ed41_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avalia��es do Procedimento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avalia��es do Procedimento ($this->ed41_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed41_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed41_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008450,'$this->ed41_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010078,1008450,'','".AddSlashes(pg_result($resaco,0,'ed41_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,1008451,'','".AddSlashes(pg_result($resaco,0,'ed41_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,1008452,'','".AddSlashes(pg_result($resaco,0,'ed41_i_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,1008453,'','".AddSlashes(pg_result($resaco,0,'ed41_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,1008454,'','".AddSlashes(pg_result($resaco,0,'ed41_i_procavalvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,1008455,'','".AddSlashes(pg_result($resaco,0,'ed41_i_procresultvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,1008456,'','".AddSlashes(pg_result($resaco,0,'ed41_c_boletim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,1008457,'','".AddSlashes(pg_result($resaco,0,'ed41_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010078,20418,'','".AddSlashes(pg_result($resaco,0,'ed41_numerodisciplinasrecuperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed41_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update procavaliacao set ";
     $virgula = "";
     if(trim($this->ed41_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_codigo"])){ 
       $sql  .= $virgula." ed41_i_codigo = $this->ed41_i_codigo ";
       $virgula = ",";
       if(trim($this->ed41_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "ed41_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed41_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procedimento"])){ 
       $sql  .= $virgula." ed41_i_procedimento = $this->ed41_i_procedimento ";
       $virgula = ",";
       if(trim($this->ed41_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento de Avalia��o n�o informado.";
         $this->erro_campo = "ed41_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed41_i_periodoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_periodoavaliacao"])){ 
       $sql  .= $virgula." ed41_i_periodoavaliacao = $this->ed41_i_periodoavaliacao ";
       $virgula = ",";
       if(trim($this->ed41_i_periodoavaliacao) == null ){ 
         $this->erro_sql = " Campo Per�odo de Avalia��o n�o informado.";
         $this->erro_campo = "ed41_i_periodoavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed41_i_formaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_formaavaliacao"])){ 
       $sql  .= $virgula." ed41_i_formaavaliacao = $this->ed41_i_formaavaliacao ";
       $virgula = ",";
       if(trim($this->ed41_i_formaavaliacao) == null ){ 
         $this->erro_sql = " Campo Forma de Avalia��o n�o informado.";
         $this->erro_campo = "ed41_i_formaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed41_i_procavalvinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procavalvinc"])){ 
        if(trim($this->ed41_i_procavalvinc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procavalvinc"])){ 
           $this->ed41_i_procavalvinc = "0" ; 
        } 
       $sql  .= $virgula." ed41_i_procavalvinc = $this->ed41_i_procavalvinc ";
       $virgula = ",";
     }
     if(trim($this->ed41_i_procresultvinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procresultvinc"])){ 
        if(trim($this->ed41_i_procresultvinc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procresultvinc"])){ 
           $this->ed41_i_procresultvinc = "0" ; 
        } 
       $sql  .= $virgula." ed41_i_procresultvinc = $this->ed41_i_procresultvinc ";
       $virgula = ",";
     }
     if(trim($this->ed41_c_boletim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_c_boletim"])){ 
       $sql  .= $virgula." ed41_c_boletim = '$this->ed41_c_boletim' ";
       $virgula = ",";
       if(trim($this->ed41_c_boletim) == null ){ 
         $this->erro_sql = " Campo Aparece no Boletim n�o informado.";
         $this->erro_campo = "ed41_c_boletim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed41_i_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_sequencia"])){ 
       $sql  .= $virgula." ed41_i_sequencia = $this->ed41_i_sequencia ";
       $virgula = ",";
       if(trim($this->ed41_i_sequencia) == null ){ 
         $this->erro_sql = " Campo Ordena��o n�o informado.";
         $this->erro_campo = "ed41_i_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed41_numerodisciplinasrecuperacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed41_numerodisciplinasrecuperacao"])){ 
        if(trim($this->ed41_numerodisciplinasrecuperacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed41_numerodisciplinasrecuperacao"])){ 
           $this->ed41_numerodisciplinasrecuperacao = "0" ; 
        } 
       $sql  .= $virgula." ed41_numerodisciplinasrecuperacao = $this->ed41_numerodisciplinasrecuperacao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed41_i_codigo!=null){
       $sql .= " ed41_i_codigo = $this->ed41_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed41_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008450,'$this->ed41_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_codigo"]) || $this->ed41_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008450,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_i_codigo'))."','$this->ed41_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procedimento"]) || $this->ed41_i_procedimento != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008451,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_i_procedimento'))."','$this->ed41_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_periodoavaliacao"]) || $this->ed41_i_periodoavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008452,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_i_periodoavaliacao'))."','$this->ed41_i_periodoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_formaavaliacao"]) || $this->ed41_i_formaavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008453,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_i_formaavaliacao'))."','$this->ed41_i_formaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procavalvinc"]) || $this->ed41_i_procavalvinc != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008454,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_i_procavalvinc'))."','$this->ed41_i_procavalvinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_procresultvinc"]) || $this->ed41_i_procresultvinc != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008455,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_i_procresultvinc'))."','$this->ed41_i_procresultvinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_c_boletim"]) || $this->ed41_c_boletim != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008456,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_c_boletim'))."','$this->ed41_c_boletim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_i_sequencia"]) || $this->ed41_i_sequencia != "")
             $resac = db_query("insert into db_acount values($acount,1010078,1008457,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_i_sequencia'))."','$this->ed41_i_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed41_numerodisciplinasrecuperacao"]) || $this->ed41_numerodisciplinasrecuperacao != "")
             $resac = db_query("insert into db_acount values($acount,1010078,20418,'".AddSlashes(pg_result($resaco,$conresaco,'ed41_numerodisciplinasrecuperacao'))."','$this->ed41_numerodisciplinasrecuperacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��es do Procedimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed41_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��es do Procedimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed41_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed41_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed41_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed41_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008450,'$ed41_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008450,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008451,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008452,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_i_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008453,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008454,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_i_procavalvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008455,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_i_procresultvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008456,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_c_boletim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,1008457,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010078,20418,'','".AddSlashes(pg_result($resaco,$iresaco,'ed41_numerodisciplinasrecuperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from procavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed41_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed41_i_codigo = $ed41_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��es do Procedimento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed41_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��es do Procedimento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed41_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed41_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:procavaliacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed41_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procavaliacao ";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procavaliacao.ed41_i_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ed41_i_codigo!=null ){
         $sql2 .= " where procavaliacao.ed41_i_codigo = $ed41_i_codigo "; 
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
   function sql_query_file ( $ed41_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed41_i_codigo!=null ){
         $sql2 .= " where procavaliacao.ed41_i_codigo = $ed41_i_codigo "; 
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
   function sql_query_regper ( $ed41_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procavaliacao ";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procavaliacao.ed41_i_procedimento";
     $sql .= "      left join regenciaperiodo  on  regenciaperiodo.ed78_i_procavaliacao =  procavaliacao.ed41_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed41_i_codigo!=null ){
         $sql2 .= " where procavaliacao.ed41_i_codigo = $ed41_i_codigo ";
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