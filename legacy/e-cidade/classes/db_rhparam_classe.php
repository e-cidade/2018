<?php
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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhparam
class cl_rhparam { 
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
   var $h36_modtermoposse = 0; 
   var $h36_instit = 0; 
   var $h36_modportariacoletiva = 0; 
   var $h36_modportariaindividual = 0; 
   var $h36_ultimaportaria = 0; 
   var $h36_intersticio = 0; 
   var $h36_pontuacaominpromocao = 0; 
   var $h36_tempocontribuicaorgps = 0; 
   var $h36_tempocontribuicaorpps = 0; 
   var $h36_temposficticios = 0; 
   var $h36_temposemcontribuicao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h36_modtermoposse = int4 = Modelo Posse 
                 h36_instit = int4 = Instituição 
                 h36_modportariacoletiva = int4 = Modelo Portaria Coletiva 
                 h36_modportariaindividual = int4 = Modelo Portaria Individual 
                 h36_ultimaportaria = int4 = Última Portaria 
                 h36_intersticio = int4 = Interstício (Anos) 
                 h36_pontuacaominpromocao = int4 = Pontuação Min. 
                 h36_tempocontribuicaorgps = int4 = Tempo de Contribuição RGPS 
                 h36_tempocontribuicaorpps = int4 = Tempo de Contribuição RPPS 
                 h36_temposficticios = int4 = Tempos Fictícios 
                 h36_temposemcontribuicao = int4 = Tempo sem Contribuição 
                 ";
   //funcao construtor da classe 
   function cl_rhparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhparam"); 
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
       $this->h36_modtermoposse = ($this->h36_modtermoposse == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_modtermoposse"]:$this->h36_modtermoposse);
       $this->h36_instit = ($this->h36_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_instit"]:$this->h36_instit);
       $this->h36_modportariacoletiva = ($this->h36_modportariacoletiva == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_modportariacoletiva"]:$this->h36_modportariacoletiva);
       $this->h36_modportariaindividual = ($this->h36_modportariaindividual == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_modportariaindividual"]:$this->h36_modportariaindividual);
       $this->h36_ultimaportaria = ($this->h36_ultimaportaria == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_ultimaportaria"]:$this->h36_ultimaportaria);
       $this->h36_intersticio = ($this->h36_intersticio == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_intersticio"]:$this->h36_intersticio);
       $this->h36_pontuacaominpromocao = ($this->h36_pontuacaominpromocao == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_pontuacaominpromocao"]:$this->h36_pontuacaominpromocao);
       $this->h36_tempocontribuicaorgps = ($this->h36_tempocontribuicaorgps == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorgps"]:$this->h36_tempocontribuicaorgps);
       $this->h36_tempocontribuicaorpps = ($this->h36_tempocontribuicaorpps == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorpps"]:$this->h36_tempocontribuicaorpps);
       $this->h36_temposficticios = ($this->h36_temposficticios == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_temposficticios"]:$this->h36_temposficticios);
       $this->h36_temposemcontribuicao = ($this->h36_temposemcontribuicao == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_temposemcontribuicao"]:$this->h36_temposemcontribuicao);
     }else{
       $this->h36_instit = ($this->h36_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["h36_instit"]:$this->h36_instit);
     }
   }
   // funcao para Inclusão
   function incluir ($h36_instit){ 
      $this->atualizacampos();
     if($this->h36_modtermoposse == null ){ 
       $this->h36_modtermoposse = "0";
     }
     if($this->h36_modportariacoletiva == null ){ 
       $this->h36_modportariacoletiva = "0";
     }
     if($this->h36_modportariaindividual == null ){ 
       $this->h36_modportariaindividual = "0";
     }
     if($this->h36_ultimaportaria == null ){ 
       $this->h36_ultimaportaria = "0";
     }
     if($this->h36_intersticio == null ){ 
       $this->erro_sql = " Campo Interstício (Anos) não informado.";
       $this->erro_campo = "h36_intersticio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h36_pontuacaominpromocao == null ){ 
       $this->erro_sql = " Campo Pontuação Min. não informado.";
       $this->erro_campo = "h36_pontuacaominpromocao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h36_tempocontribuicaorgps == null ){ 
       $this->h36_tempocontribuicaorgps = 'null';
     }
     if($this->h36_tempocontribuicaorpps == null ){ 
       $this->h36_tempocontribuicaorpps = 'null';
     }
     if($this->h36_temposficticios == null ){ 
       $this->h36_temposficticios = 'null';
     }
     if($this->h36_temposemcontribuicao == null ){ 
       $this->h36_temposemcontribuicao = 'null';
     }
       $this->h36_instit = $h36_instit; 
     if(($this->h36_instit == null) || ($this->h36_instit == "") ){ 
       $this->erro_sql = " Campo h36_instit não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhparam(
                                       h36_modtermoposse 
                                      ,h36_instit 
                                      ,h36_modportariacoletiva 
                                      ,h36_modportariaindividual 
                                      ,h36_ultimaportaria 
                                      ,h36_intersticio 
                                      ,h36_pontuacaominpromocao 
                                      ,h36_tempocontribuicaorgps 
                                      ,h36_tempocontribuicaorpps 
                                      ,h36_temposficticios 
                                      ,h36_temposemcontribuicao 
                       )
                values (
                                $this->h36_modtermoposse 
                               ,$this->h36_instit 
                               ,$this->h36_modportariacoletiva 
                               ,$this->h36_modportariaindividual 
                               ,$this->h36_ultimaportaria 
                               ,$this->h36_intersticio 
                               ,$this->h36_pontuacaominpromocao 
                               ,$this->h36_tempocontribuicaorgps 
                               ,$this->h36_tempocontribuicaorpps 
                               ,$this->h36_temposficticios 
                               ,$this->h36_temposemcontribuicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros RH ($this->h36_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros RH já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros RH ($this->h36_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h36_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h36_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12193,'$this->h36_instit','I')");
         $resac = db_query("insert into db_acount values($acount,2117,12192,'','".AddSlashes(pg_result($resaco,0,'h36_modtermoposse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,12193,'','".AddSlashes(pg_result($resaco,0,'h36_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,12194,'','".AddSlashes(pg_result($resaco,0,'h36_modportariacoletiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,12195,'','".AddSlashes(pg_result($resaco,0,'h36_modportariaindividual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,12196,'','".AddSlashes(pg_result($resaco,0,'h36_ultimaportaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,18738,'','".AddSlashes(pg_result($resaco,0,'h36_intersticio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,18739,'','".AddSlashes(pg_result($resaco,0,'h36_pontuacaominpromocao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,21955,'','".AddSlashes(pg_result($resaco,0,'h36_tempocontribuicaorgps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,21956,'','".AddSlashes(pg_result($resaco,0,'h36_tempocontribuicaorpps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,21957,'','".AddSlashes(pg_result($resaco,0,'h36_temposficticios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2117,21958,'','".AddSlashes(pg_result($resaco,0,'h36_temposemcontribuicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h36_instit=null) { 
      $this->atualizacampos();
     $sql = " update rhparam set ";
     $virgula = "";
     if(trim($this->h36_modtermoposse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_modtermoposse"])){ 
        if(trim($this->h36_modtermoposse)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_modtermoposse"])){ 
           $this->h36_modtermoposse = "0" ; 
        } 
       $sql  .= $virgula." h36_modtermoposse = $this->h36_modtermoposse ";
       $virgula = ",";
     }
     if(trim($this->h36_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_instit"])){ 
       $sql  .= $virgula." h36_instit = $this->h36_instit ";
       $virgula = ",";
       if(trim($this->h36_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "h36_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h36_modportariacoletiva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_modportariacoletiva"])){ 
        if(trim($this->h36_modportariacoletiva)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_modportariacoletiva"])){ 
           $this->h36_modportariacoletiva = "0" ; 
        } 
       $sql  .= $virgula." h36_modportariacoletiva = $this->h36_modportariacoletiva ";
       $virgula = ",";
     }
     if(trim($this->h36_modportariaindividual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_modportariaindividual"])){ 
        if(trim($this->h36_modportariaindividual)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_modportariaindividual"])){ 
           $this->h36_modportariaindividual = "0" ; 
        } 
       $sql  .= $virgula." h36_modportariaindividual = $this->h36_modportariaindividual ";
       $virgula = ",";
     }
     if(trim($this->h36_ultimaportaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_ultimaportaria"])){ 
        if(trim($this->h36_ultimaportaria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_ultimaportaria"])){ 
           $this->h36_ultimaportaria = "0" ; 
        } 
       $sql  .= $virgula." h36_ultimaportaria = $this->h36_ultimaportaria ";
       $virgula = ",";
     }
     if(trim($this->h36_intersticio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_intersticio"])){ 
       $sql  .= $virgula." h36_intersticio = $this->h36_intersticio ";
       $virgula = ",";
       if(trim($this->h36_intersticio) == null ){ 
         $this->erro_sql = " Campo Interstício (Anos) não informado.";
         $this->erro_campo = "h36_intersticio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h36_pontuacaominpromocao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_pontuacaominpromocao"])){ 
       $sql  .= $virgula." h36_pontuacaominpromocao = $this->h36_pontuacaominpromocao ";
       $virgula = ",";
       if(trim($this->h36_pontuacaominpromocao) == null ){ 
         $this->erro_sql = " Campo Pontuação Min. não informado.";
         $this->erro_campo = "h36_pontuacaominpromocao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h36_tempocontribuicaorgps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorgps"])){ 
        if(trim($this->h36_tempocontribuicaorgps)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorgps"])){ 
           $this->h36_tempocontribuicaorgps = 'null' ;
        } 
       $sql  .= $virgula." h36_tempocontribuicaorgps = $this->h36_tempocontribuicaorgps ";
       $virgula = ",";
     }
     if(trim($this->h36_tempocontribuicaorpps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorpps"])){ 
        if(trim($this->h36_tempocontribuicaorpps)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorpps"])){ 
           $this->h36_tempocontribuicaorpps = 'null' ;
        } 
       $sql  .= $virgula." h36_tempocontribuicaorpps = $this->h36_tempocontribuicaorpps ";
       $virgula = ",";
     }
     if(trim($this->h36_temposficticios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_temposficticios"])){ 
        if(trim($this->h36_temposficticios)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_temposficticios"])){ 
           $this->h36_temposficticios = 'null' ;
        } 
       $sql  .= $virgula." h36_temposficticios = $this->h36_temposficticios ";
       $virgula = ",";
     }
     if(trim($this->h36_temposemcontribuicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h36_temposemcontribuicao"])){ 
        if(trim($this->h36_temposemcontribuicao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h36_temposemcontribuicao"])){ 
           $this->h36_temposemcontribuicao = 'null' ;
        } 
       $sql  .= $virgula." h36_temposemcontribuicao = $this->h36_temposemcontribuicao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h36_instit!=null){
       $sql .= " h36_instit = $this->h36_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h36_instit));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12193,'$this->h36_instit','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_modtermoposse"]) || $this->h36_modtermoposse != "")
             $resac = db_query("insert into db_acount values($acount,2117,12192,'".AddSlashes(pg_result($resaco,$conresaco,'h36_modtermoposse'))."','$this->h36_modtermoposse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_instit"]) || $this->h36_instit != "")
             $resac = db_query("insert into db_acount values($acount,2117,12193,'".AddSlashes(pg_result($resaco,$conresaco,'h36_instit'))."','$this->h36_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_modportariacoletiva"]) || $this->h36_modportariacoletiva != "")
             $resac = db_query("insert into db_acount values($acount,2117,12194,'".AddSlashes(pg_result($resaco,$conresaco,'h36_modportariacoletiva'))."','$this->h36_modportariacoletiva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_modportariaindividual"]) || $this->h36_modportariaindividual != "")
             $resac = db_query("insert into db_acount values($acount,2117,12195,'".AddSlashes(pg_result($resaco,$conresaco,'h36_modportariaindividual'))."','$this->h36_modportariaindividual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_ultimaportaria"]) || $this->h36_ultimaportaria != "")
             $resac = db_query("insert into db_acount values($acount,2117,12196,'".AddSlashes(pg_result($resaco,$conresaco,'h36_ultimaportaria'))."','$this->h36_ultimaportaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_intersticio"]) || $this->h36_intersticio != "")
             $resac = db_query("insert into db_acount values($acount,2117,18738,'".AddSlashes(pg_result($resaco,$conresaco,'h36_intersticio'))."','$this->h36_intersticio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_pontuacaominpromocao"]) || $this->h36_pontuacaominpromocao != "")
             $resac = db_query("insert into db_acount values($acount,2117,18739,'".AddSlashes(pg_result($resaco,$conresaco,'h36_pontuacaominpromocao'))."','$this->h36_pontuacaominpromocao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorgps"]) || $this->h36_tempocontribuicaorgps != "")
             $resac = db_query("insert into db_acount values($acount,2117,21955,'".AddSlashes(pg_result($resaco,$conresaco,'h36_tempocontribuicaorgps'))."','$this->h36_tempocontribuicaorgps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_tempocontribuicaorpps"]) || $this->h36_tempocontribuicaorpps != "")
             $resac = db_query("insert into db_acount values($acount,2117,21956,'".AddSlashes(pg_result($resaco,$conresaco,'h36_tempocontribuicaorpps'))."','$this->h36_tempocontribuicaorpps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_temposficticios"]) || $this->h36_temposficticios != "")
             $resac = db_query("insert into db_acount values($acount,2117,21957,'".AddSlashes(pg_result($resaco,$conresaco,'h36_temposficticios'))."','$this->h36_temposficticios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h36_temposemcontribuicao"]) || $this->h36_temposemcontribuicao != "")
             $resac = db_query("insert into db_acount values($acount,2117,21958,'".AddSlashes(pg_result($resaco,$conresaco,'h36_temposemcontribuicao'))."','$this->h36_temposemcontribuicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros RH não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h36_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros RH não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h36_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h36_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h36_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($h36_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12193,'$h36_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,2117,12192,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_modtermoposse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,12193,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,12194,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_modportariacoletiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,12195,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_modportariaindividual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,12196,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_ultimaportaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,18738,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_intersticio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,18739,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_pontuacaominpromocao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,21955,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_tempocontribuicaorgps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,21956,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_tempocontribuicaorpps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,21957,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_temposficticios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2117,21958,'','".AddSlashes(pg_result($resaco,$iresaco,'h36_temposemcontribuicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhparam
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($h36_instit)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h36_instit = $h36_instit ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros RH não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h36_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros RH não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h36_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h36_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($h36_instit = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhparam ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhparam.h36_instit";
     $sql .= "      left  join tipoasse  on  tipoasse.h12_codigo = rhparam.h36_temposemcontribuicao and  tipoasse.h12_codigo = rhparam.h36_tempocontribuicaorgps and  tipoasse.h12_codigo = rhparam.h36_tempocontribuicaorpps and  tipoasse.h12_codigo = rhparam.h36_temposficticios";
     $sql .= "      left  join db_relatorio  on  db_relatorio.db63_sequencial = rhparam.h36_modtermoposse and  db_relatorio.db63_sequencial = rhparam.h36_modportariacoletiva and  db_relatorio.db63_sequencial = rhparam.h36_modportariaindividual";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_gruporelatorio  on  db_gruporelatorio.db13_sequencial = db_relatorio.db63_db_gruporelatorio";
     $sql .= "      inner join db_tiporelatorio  on  db_tiporelatorio.db14_sequencial = db_relatorio.db63_db_tiporelatorio";
     $sql .= "      inner join db_relatorioorigem  on  db_relatorioorigem.db16_sequencial = db_relatorio.db63_db_relatorioorigem";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h36_instit)) {
         $sql2 .= " where rhparam.h36_instit = $h36_instit "; 
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
   public function sql_query_file ($h36_instit = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhparam ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h36_instit)){
         $sql2 .= " where rhparam.h36_instit = $h36_instit "; 
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

  function sql_query_rhparam ( $oid = null,$campos="rhparam.oid,*",$ordem=null,$dbwhere=""){
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
    $sql .= " from rhparam ";
    $sql .= "   inner join db_config             on db_config.codigo  = rhparam.h36_instit                ";
    $sql .= "   inner join db_relatorio      a   on a.db63_sequencial = rhparam.h36_modtermoposse         ";
    $sql .= "   inner join db_relatorio      b   on b.db63_sequencial = rhparam.h36_modportariacoletiva   ";
    $sql .= "   inner join db_relatorio      c   on c.db63_sequencial = rhparam.h36_modportariaindividual ";
    $sql .= "   inner join cgm                   on cgm.z01_numcgm    = db_config.numcgm                  ";
    $sql .= "   inner join db_gruporelatorio d   on d.db13_sequencial = a.db63_db_gruporelatorio          ";
    $sql .= "   inner join db_tiporelatorio  e   on e.db14_sequencial = a.db63_db_tiporelatorio           ";
    $sql .= "   inner join db_gruporelatorio f   on f.db13_sequencial = b.db63_db_gruporelatorio          ";
    $sql .= "   inner join db_tiporelatorio  g   on g.db14_sequencial = b.db63_db_tiporelatorio           ";
    $sql .= "   inner join db_gruporelatorio h   on h.db13_sequencial = c.db63_db_gruporelatorio          ";
    $sql .= "   inner join db_tiporelatorio  i   on i.db14_sequencial = c.db63_db_tiporelatorio           ";
    $sql2 = "";
    if($dbwhere==""){
      if( $oid != "" && $oid != null){
        $sql2 = " where rhparam.oid = '$oid'";
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
