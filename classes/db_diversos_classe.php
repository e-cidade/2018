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

//MODULO: diversos
//CLASSE DA ENTIDADE diversos
class cl_diversos { 
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
   var $dv05_coddiver = 0; 
   var $dv05_numcgm = 0; 
   var $dv05_dtinsc_dia = null; 
   var $dv05_dtinsc_mes = null; 
   var $dv05_dtinsc_ano = null; 
   var $dv05_dtinsc = null; 
   var $dv05_exerc = 0; 
   var $dv05_numpre = 0; 
   var $dv05_vlrhis = 0; 
   var $dv05_procdiver = 0; 
   var $dv05_numtot = 0; 
   var $dv05_privenc_dia = null; 
   var $dv05_privenc_mes = null; 
   var $dv05_privenc_ano = null; 
   var $dv05_privenc = null; 
   var $dv05_provenc_dia = null; 
   var $dv05_provenc_mes = null; 
   var $dv05_provenc_ano = null; 
   var $dv05_provenc = null; 
   var $dv05_diaprox = 0; 
   var $dv05_oper_dia = null; 
   var $dv05_oper_mes = null; 
   var $dv05_oper_ano = null; 
   var $dv05_oper = null; 
   var $dv05_valor = 0; 
   var $dv05_obs = null; 
   var $dv05_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 dv05_coddiver = int4 = Código do diversos 
                 dv05_numcgm = int4 = Numcgm 
                 dv05_dtinsc = date = Data da Inscrição 
                 dv05_exerc = int4 = Ano de Origem 
                 dv05_numpre = int4 = Código de Arrecadação 
                 dv05_vlrhis = float8 = Valor Histórico 
                 dv05_procdiver = int4 = Procedência 
                 dv05_numtot = int4 = Numero de parcelas 
                 dv05_privenc = date = Primeiro vencimento 
                 dv05_provenc = date = Próximo vencimento 
                 dv05_diaprox = int4 = Dia dos próximos vencimentos 
                 dv05_oper = date = Data da Operação 
                 dv05_valor = float8 = Valor Corrigido 
                 dv05_obs = text = Observações 
                 dv05_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_diversos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diversos"); 
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
       $this->dv05_coddiver = ($this->dv05_coddiver == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_coddiver"]:$this->dv05_coddiver);
       $this->dv05_numcgm = ($this->dv05_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_numcgm"]:$this->dv05_numcgm);
       if($this->dv05_dtinsc == ""){
         $this->dv05_dtinsc_dia = ($this->dv05_dtinsc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_dtinsc_dia"]:$this->dv05_dtinsc_dia);
         $this->dv05_dtinsc_mes = ($this->dv05_dtinsc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_dtinsc_mes"]:$this->dv05_dtinsc_mes);
         $this->dv05_dtinsc_ano = ($this->dv05_dtinsc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_dtinsc_ano"]:$this->dv05_dtinsc_ano);
         if($this->dv05_dtinsc_dia != ""){
            $this->dv05_dtinsc = $this->dv05_dtinsc_ano."-".$this->dv05_dtinsc_mes."-".$this->dv05_dtinsc_dia;
         }
       }
       $this->dv05_exerc = ($this->dv05_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_exerc"]:$this->dv05_exerc);
       $this->dv05_numpre = ($this->dv05_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_numpre"]:$this->dv05_numpre);
       $this->dv05_vlrhis = ($this->dv05_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_vlrhis"]:$this->dv05_vlrhis);
       $this->dv05_procdiver = ($this->dv05_procdiver == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_procdiver"]:$this->dv05_procdiver);
       $this->dv05_numtot = ($this->dv05_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_numtot"]:$this->dv05_numtot);
       if($this->dv05_privenc == ""){
         $this->dv05_privenc_dia = ($this->dv05_privenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_privenc_dia"]:$this->dv05_privenc_dia);
         $this->dv05_privenc_mes = ($this->dv05_privenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_privenc_mes"]:$this->dv05_privenc_mes);
         $this->dv05_privenc_ano = ($this->dv05_privenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_privenc_ano"]:$this->dv05_privenc_ano);
         if($this->dv05_privenc_dia != ""){
            $this->dv05_privenc = $this->dv05_privenc_ano."-".$this->dv05_privenc_mes."-".$this->dv05_privenc_dia;
         }
       }
       if($this->dv05_provenc == ""){
         $this->dv05_provenc_dia = ($this->dv05_provenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_provenc_dia"]:$this->dv05_provenc_dia);
         $this->dv05_provenc_mes = ($this->dv05_provenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_provenc_mes"]:$this->dv05_provenc_mes);
         $this->dv05_provenc_ano = ($this->dv05_provenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_provenc_ano"]:$this->dv05_provenc_ano);
         if($this->dv05_provenc_dia != ""){
            $this->dv05_provenc = $this->dv05_provenc_ano."-".$this->dv05_provenc_mes."-".$this->dv05_provenc_dia;
         }
       }
       $this->dv05_diaprox = ($this->dv05_diaprox == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_diaprox"]:$this->dv05_diaprox);
       if($this->dv05_oper == ""){
         $this->dv05_oper_dia = ($this->dv05_oper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_oper_dia"]:$this->dv05_oper_dia);
         $this->dv05_oper_mes = ($this->dv05_oper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_oper_mes"]:$this->dv05_oper_mes);
         $this->dv05_oper_ano = ($this->dv05_oper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_oper_ano"]:$this->dv05_oper_ano);
         if($this->dv05_oper_dia != ""){
            $this->dv05_oper = $this->dv05_oper_ano."-".$this->dv05_oper_mes."-".$this->dv05_oper_dia;
         }
       }
       $this->dv05_valor = ($this->dv05_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_valor"]:$this->dv05_valor);
       $this->dv05_obs = ($this->dv05_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_obs"]:$this->dv05_obs);
       $this->dv05_instit = ($this->dv05_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_instit"]:$this->dv05_instit);
     }else{
       $this->dv05_coddiver = ($this->dv05_coddiver == ""?@$GLOBALS["HTTP_POST_VARS"]["dv05_coddiver"]:$this->dv05_coddiver);
     }
   }
   // funcao para inclusao
   function incluir ($dv05_coddiver){ 
      $this->atualizacampos();
     if($this->dv05_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "dv05_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_dtinsc == null ){ 
       $this->erro_sql = " Campo Data da Inscrição nao Informado.";
       $this->erro_campo = "dv05_dtinsc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_exerc == null ){ 
       $this->erro_sql = " Campo Ano de Origem nao Informado.";
       $this->erro_campo = "dv05_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_numpre == null ){ 
       $this->erro_sql = " Campo Código de Arrecadação nao Informado.";
       $this->erro_campo = "dv05_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_vlrhis == null ){ 
       $this->erro_sql = " Campo Valor Histórico nao Informado.";
       $this->erro_campo = "dv05_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_procdiver == null ){ 
       $this->erro_sql = " Campo Procedência nao Informado.";
       $this->erro_campo = "dv05_procdiver";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_numtot == null ){ 
       $this->erro_sql = " Campo Numero de parcelas nao Informado.";
       $this->erro_campo = "dv05_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_privenc == null ){ 
       $this->erro_sql = " Campo Primeiro vencimento nao Informado.";
       $this->erro_campo = "dv05_privenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_provenc == null ){ 
       $this->erro_sql = " Campo Próximo vencimento nao Informado.";
       $this->erro_campo = "dv05_provenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_diaprox == null ){ 
       $this->erro_sql = " Campo Dia dos próximos vencimentos nao Informado.";
       $this->erro_campo = "dv05_diaprox";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_oper == null ){ 
       $this->erro_sql = " Campo Data da Operação nao Informado.";
       $this->erro_campo = "dv05_oper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_valor == null ){ 
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "dv05_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv05_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "dv05_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($dv05_coddiver == "" || $dv05_coddiver == null ){
       $result = db_query("select nextval('diversos_dv05_coddiver_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diversos_dv05_coddiver_seq do campo: dv05_coddiver"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->dv05_coddiver = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diversos_dv05_coddiver_seq");
       if(($result != false) && (pg_result($result,0,0) < $dv05_coddiver)){
         $this->erro_sql = " Campo dv05_coddiver maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->dv05_coddiver = $dv05_coddiver; 
       }
     }
     if(($this->dv05_coddiver == null) || ($this->dv05_coddiver == "") ){ 
       $this->erro_sql = " Campo dv05_coddiver nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diversos(
                                       dv05_coddiver 
                                      ,dv05_numcgm 
                                      ,dv05_dtinsc 
                                      ,dv05_exerc 
                                      ,dv05_numpre 
                                      ,dv05_vlrhis 
                                      ,dv05_procdiver 
                                      ,dv05_numtot 
                                      ,dv05_privenc 
                                      ,dv05_provenc 
                                      ,dv05_diaprox 
                                      ,dv05_oper 
                                      ,dv05_valor 
                                      ,dv05_obs 
                                      ,dv05_instit 
                       )
                values (
                                $this->dv05_coddiver 
                               ,$this->dv05_numcgm 
                               ,".($this->dv05_dtinsc == "null" || $this->dv05_dtinsc == ""?"null":"'".$this->dv05_dtinsc."'")." 
                               ,$this->dv05_exerc 
                               ,$this->dv05_numpre 
                               ,$this->dv05_vlrhis 
                               ,$this->dv05_procdiver 
                               ,$this->dv05_numtot 
                               ,".($this->dv05_privenc == "null" || $this->dv05_privenc == ""?"null":"'".$this->dv05_privenc."'")." 
                               ,".($this->dv05_provenc == "null" || $this->dv05_provenc == ""?"null":"'".$this->dv05_provenc."'")." 
                               ,$this->dv05_diaprox 
                               ,".($this->dv05_oper == "null" || $this->dv05_oper == ""?"null":"'".$this->dv05_oper."'")." 
                               ,$this->dv05_valor 
                               ,'$this->dv05_obs' 
                               ,$this->dv05_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "diversos debitos ($this->dv05_coddiver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "diversos debitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "diversos debitos ($this->dv05_coddiver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv05_coddiver;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->dv05_coddiver));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3470,'$this->dv05_coddiver','I')");
       $resac = db_query("insert into db_acount values($acount,372,3470,'','".AddSlashes(pg_result($resaco,0,'dv05_coddiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3471,'','".AddSlashes(pg_result($resaco,0,'dv05_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3472,'','".AddSlashes(pg_result($resaco,0,'dv05_dtinsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3473,'','".AddSlashes(pg_result($resaco,0,'dv05_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3474,'','".AddSlashes(pg_result($resaco,0,'dv05_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3478,'','".AddSlashes(pg_result($resaco,0,'dv05_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3479,'','".AddSlashes(pg_result($resaco,0,'dv05_procdiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,4760,'','".AddSlashes(pg_result($resaco,0,'dv05_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,4761,'','".AddSlashes(pg_result($resaco,0,'dv05_privenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3481,'','".AddSlashes(pg_result($resaco,0,'dv05_provenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,4762,'','".AddSlashes(pg_result($resaco,0,'dv05_diaprox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3482,'','".AddSlashes(pg_result($resaco,0,'dv05_oper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3483,'','".AddSlashes(pg_result($resaco,0,'dv05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,3480,'','".AddSlashes(pg_result($resaco,0,'dv05_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,372,10552,'','".AddSlashes(pg_result($resaco,0,'dv05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($dv05_coddiver=null) { 
      $this->atualizacampos();
     $sql = " update diversos set ";
     $virgula = "";
     if(trim($this->dv05_coddiver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_coddiver"])){ 
       $sql  .= $virgula." dv05_coddiver = $this->dv05_coddiver ";
       $virgula = ",";
       if(trim($this->dv05_coddiver) == null ){ 
         $this->erro_sql = " Campo Código do diversos nao Informado.";
         $this->erro_campo = "dv05_coddiver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_numcgm"])){ 
       $sql  .= $virgula." dv05_numcgm = $this->dv05_numcgm ";
       $virgula = ",";
       if(trim($this->dv05_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "dv05_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_dtinsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_dtinsc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dv05_dtinsc_dia"] !="") ){ 
       $sql  .= $virgula." dv05_dtinsc = '$this->dv05_dtinsc' ";
       $virgula = ",";
       if(trim($this->dv05_dtinsc) == null ){ 
         $this->erro_sql = " Campo Data da Inscrição nao Informado.";
         $this->erro_campo = "dv05_dtinsc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_dtinsc_dia"])){ 
         $sql  .= $virgula." dv05_dtinsc = null ";
         $virgula = ",";
         if(trim($this->dv05_dtinsc) == null ){ 
           $this->erro_sql = " Campo Data da Inscrição nao Informado.";
           $this->erro_campo = "dv05_dtinsc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dv05_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_exerc"])){ 
       $sql  .= $virgula." dv05_exerc = $this->dv05_exerc ";
       $virgula = ",";
       if(trim($this->dv05_exerc) == null ){ 
         $this->erro_sql = " Campo Ano de Origem nao Informado.";
         $this->erro_campo = "dv05_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_numpre"])){ 
       $sql  .= $virgula." dv05_numpre = $this->dv05_numpre ";
       $virgula = ",";
       if(trim($this->dv05_numpre) == null ){ 
         $this->erro_sql = " Campo Código de Arrecadação nao Informado.";
         $this->erro_campo = "dv05_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_vlrhis"])){ 
       $sql  .= $virgula." dv05_vlrhis = $this->dv05_vlrhis ";
       $virgula = ",";
       if(trim($this->dv05_vlrhis) == null ){ 
         $this->erro_sql = " Campo Valor Histórico nao Informado.";
         $this->erro_campo = "dv05_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_procdiver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_procdiver"])){ 
       $sql  .= $virgula." dv05_procdiver = $this->dv05_procdiver ";
       $virgula = ",";
       if(trim($this->dv05_procdiver) == null ){ 
         $this->erro_sql = " Campo Procedência nao Informado.";
         $this->erro_campo = "dv05_procdiver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_numtot"])){ 
       $sql  .= $virgula." dv05_numtot = $this->dv05_numtot ";
       $virgula = ",";
       if(trim($this->dv05_numtot) == null ){ 
         $this->erro_sql = " Campo Numero de parcelas nao Informado.";
         $this->erro_campo = "dv05_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_privenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_privenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dv05_privenc_dia"] !="") ){ 
       $sql  .= $virgula." dv05_privenc = '$this->dv05_privenc' ";
       $virgula = ",";
       if(trim($this->dv05_privenc) == null ){ 
         $this->erro_sql = " Campo Primeiro vencimento nao Informado.";
         $this->erro_campo = "dv05_privenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_privenc_dia"])){ 
         $sql  .= $virgula." dv05_privenc = null ";
         $virgula = ",";
         if(trim($this->dv05_privenc) == null ){ 
           $this->erro_sql = " Campo Primeiro vencimento nao Informado.";
           $this->erro_campo = "dv05_privenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dv05_provenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_provenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dv05_provenc_dia"] !="") ){ 
       $sql  .= $virgula." dv05_provenc = '$this->dv05_provenc' ";
       $virgula = ",";
       if(trim($this->dv05_provenc) == null ){ 
         $this->erro_sql = " Campo Próximo vencimento nao Informado.";
         $this->erro_campo = "dv05_provenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_provenc_dia"])){ 
         $sql  .= $virgula." dv05_provenc = null ";
         $virgula = ",";
         if(trim($this->dv05_provenc) == null ){ 
           $this->erro_sql = " Campo Próximo vencimento nao Informado.";
           $this->erro_campo = "dv05_provenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dv05_diaprox)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_diaprox"])){ 
       $sql  .= $virgula." dv05_diaprox = $this->dv05_diaprox ";
       $virgula = ",";
       if(trim($this->dv05_diaprox) == null ){ 
         $this->erro_sql = " Campo Dia dos próximos vencimentos nao Informado.";
         $this->erro_campo = "dv05_diaprox";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_oper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_oper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dv05_oper_dia"] !="") ){ 
       $sql  .= $virgula." dv05_oper = '$this->dv05_oper' ";
       $virgula = ",";
       if(trim($this->dv05_oper) == null ){ 
         $this->erro_sql = " Campo Data da Operação nao Informado.";
         $this->erro_campo = "dv05_oper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_oper_dia"])){ 
         $sql  .= $virgula." dv05_oper = null ";
         $virgula = ",";
         if(trim($this->dv05_oper) == null ){ 
           $this->erro_sql = " Campo Data da Operação nao Informado.";
           $this->erro_campo = "dv05_oper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dv05_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_valor"])){ 
       $sql  .= $virgula." dv05_valor = $this->dv05_valor ";
       $virgula = ",";
       if(trim($this->dv05_valor) == null ){ 
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "dv05_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv05_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_obs"])){ 
       $sql  .= $virgula." dv05_obs = '$this->dv05_obs' ";
       $virgula = ",";
     }
     if(trim($this->dv05_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv05_instit"])){ 
       $sql  .= $virgula." dv05_instit = $this->dv05_instit ";
       $virgula = ",";
       if(trim($this->dv05_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "dv05_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($dv05_coddiver!=null){
       $sql .= " dv05_coddiver = $this->dv05_coddiver";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->dv05_coddiver));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3470,'$this->dv05_coddiver','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_coddiver"]) || $this->dv05_coddiver != "")
           $resac = db_query("insert into db_acount values($acount,372,3470,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_coddiver'))."','$this->dv05_coddiver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_numcgm"]) || $this->dv05_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,372,3471,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_numcgm'))."','$this->dv05_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_dtinsc"]) || $this->dv05_dtinsc != "")
           $resac = db_query("insert into db_acount values($acount,372,3472,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_dtinsc'))."','$this->dv05_dtinsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_exerc"]) || $this->dv05_exerc != "")
           $resac = db_query("insert into db_acount values($acount,372,3473,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_exerc'))."','$this->dv05_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_numpre"]) || $this->dv05_numpre != "")
           $resac = db_query("insert into db_acount values($acount,372,3474,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_numpre'))."','$this->dv05_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_vlrhis"]) || $this->dv05_vlrhis != "")
           $resac = db_query("insert into db_acount values($acount,372,3478,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_vlrhis'))."','$this->dv05_vlrhis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_procdiver"]) || $this->dv05_procdiver != "")
           $resac = db_query("insert into db_acount values($acount,372,3479,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_procdiver'))."','$this->dv05_procdiver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_numtot"]) || $this->dv05_numtot != "")
           $resac = db_query("insert into db_acount values($acount,372,4760,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_numtot'))."','$this->dv05_numtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_privenc"]) || $this->dv05_privenc != "")
           $resac = db_query("insert into db_acount values($acount,372,4761,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_privenc'))."','$this->dv05_privenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_provenc"]) || $this->dv05_provenc != "")
           $resac = db_query("insert into db_acount values($acount,372,3481,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_provenc'))."','$this->dv05_provenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_diaprox"]) || $this->dv05_diaprox != "")
           $resac = db_query("insert into db_acount values($acount,372,4762,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_diaprox'))."','$this->dv05_diaprox',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_oper"]) || $this->dv05_oper != "")
           $resac = db_query("insert into db_acount values($acount,372,3482,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_oper'))."','$this->dv05_oper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_valor"]) || $this->dv05_valor != "")
           $resac = db_query("insert into db_acount values($acount,372,3483,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_valor'))."','$this->dv05_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_obs"]) || $this->dv05_obs != "")
           $resac = db_query("insert into db_acount values($acount,372,3480,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_obs'))."','$this->dv05_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv05_instit"]) || $this->dv05_instit != "")
           $resac = db_query("insert into db_acount values($acount,372,10552,'".AddSlashes(pg_result($resaco,$conresaco,'dv05_instit'))."','$this->dv05_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diversos debitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv05_coddiver;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diversos debitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv05_coddiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv05_coddiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($dv05_coddiver=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($dv05_coddiver));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3470,'$dv05_coddiver','E')");
         $resac = db_query("insert into db_acount values($acount,372,3470,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_coddiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3471,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3472,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_dtinsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3473,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3474,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3478,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3479,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_procdiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,4760,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,4761,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_privenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3481,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_provenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,4762,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_diaprox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3482,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_oper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3483,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,3480,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,372,10552,'','".AddSlashes(pg_result($resaco,$iresaco,'dv05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diversos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($dv05_coddiver != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " dv05_coddiver = $dv05_coddiver ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diversos debitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$dv05_coddiver;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diversos debitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$dv05_coddiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$dv05_coddiver;
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
        $this->erro_sql   = "Record Vazio na Tabela:diversos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $dv05_coddiver=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diversos ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = diversos.dv05_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = diversos.dv05_instit";
     $sql .= "      inner join procdiver  on  procdiver.dv09_procdiver = diversos.dv05_procdiver";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = procdiver.dv09_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = procdiver.dv09_receit";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = procdiver.dv09_tipo";
     $sql .= "      inner join db_config  as a on   a.codigo = procdiver.dv09_instit";
     $sql .= "      inner join proced  on  proced.v03_codigo = procdiver.dv09_proced";
     $sql2 = "";
     if($dbwhere==""){
       if($dv05_coddiver!=null ){
         $sql2 .= " where diversos.dv05_coddiver = $dv05_coddiver "; 
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
   function sql_query_file ( $dv05_coddiver=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diversos ";
     $sql2 = "";
     if($dbwhere==""){
       if($dv05_coddiver!=null ){
         $sql2 .= " where diversos.dv05_coddiver = $dv05_coddiver "; 
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
   function sql_pesquisa( $campos="",$ordem=null,$dbwhere=""){
     if($ordem != "" ){
       $ordem="order by $ordem ";
    }
    if($campos != "" ){
       $campos=" $campos, ";
    }else{
       $campos=" *, ";
    }
     if($dbwhere != "" ){
       $dbwhere= " where $dbwhere ";
    }
      $sql= "
             select $campos
                    case when arrematric.k00_numpre is null and arreinscr.k00_numpre is null  then 'CGM'
                    else case when arrematric.k00_numpre is null then 'INSCRICAO'
                    else 'MATRICULA'  end end as k00_tipo
               from diversos
                    left join  arrematric on diversos.dv05_numpre  = arrematric.k00_numpre
                    left join  arreinscr  on diversos.dv05_numpre  = arreinscr.k00_numpre
                    inner join procdiver  on dv09_procdiver        = dv05_procdiver
                    left join  arrenumcgm on diversos.dv05_numpre  = arrenumcgm.k00_numpre
                    inner join cgm        on cgm.z01_numcgm        = arrenumcgm.k00_numcgm
              $dbwhere
              $ordem
            ";
           return $sql;
    }
   function sql_query_func ( $dv05_coddiver=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diversos ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = diversos.dv05_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = diversos.dv05_instit";
     $sql .= "      inner join procdiver  on  procdiver.dv09_procdiver = diversos.dv05_procdiver";
     $sql .= "      inner join cgm c  on  c.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = procdiver.dv09_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = procdiver.dv09_receit";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = procdiver.dv09_tipo";
     $sql .= "      inner join db_config  as a on   a.codigo = procdiver.dv09_instit";
     $sql .= "      inner join proced  on  proced.v03_codigo = procdiver.dv09_proced";
		 $sql .= "      left join arreinscr on dv05_numpre               = arreinscr.k00_numpre ";
     $sql .= "      left join arrematric on dv05_numpre              = arrematric.k00_numpre ";
     $sql2 = "";
     if($dbwhere==""){
       if($dv05_coddiver!=null ){
         $sql2 .= " where diversos.dv05_coddiver = $dv05_coddiver "; 
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
   function sql_query_relatorio( $campos="",$ordem=null,$dbwhere="",$instit=null){
    $whereinstit = "";   
		if($instit!=null){
			$whereinstit = " where dv05_instit = $instit ";
		}

    if($ordem != "" ){
      $ordem="order by $ordem ";
    }
    if($campos != "" ){
      $campos=" $campos, ";
    }else{
      $campos=" *, ";
    }
    if($dbwhere != "" ){
      $dbwhere= " where $dbwhere ";
    }
    $sql= " select $campos
                   case 
 				             when arrematric.k00_numpre is not null then  
                       'MATRICULA'
           			     when arreinscr.k00_numpre  is not null then  
                       'INSCRICAO'
 			               when arrenumcgm.k00_numpre is not null then 
                        'CGM'
  				         end as k00_tipo,
 									 case  
 									   when arrecad.k00_numpre  is not null then 
                       arrecad.k00_dtvenc
 									   when arrecant.k00_numpre	is not null then 
                       arrecant.k00_dtvenc
                   end as dtvenc							
              from (  select dv05_coddiver  ,
                             dv05_numcgm    ,
                             dv05_dtinsc    ,
                             dv05_exerc     ,
                             case
                               when v07_numpre is not null then
                                 v07_numpre
                               else
                                 dv05_numpre
                             end as dv05_numpre ,
                             dv05_vlrhis    ,
                             dv05_procdiver ,
                             dv05_numtot    ,
                             dv05_privenc   ,
                             dv05_provenc   ,
                             dv05_diaprox   ,
                             dv05_oper      ,
                             dv05_valor     ,
                             dv05_obs       ,
                             termodiver.*,
                             termo.*
                      from diversos
                           left join termodiver on dv10_coddiver = dv05_coddiver
                           left join termo      on v07_parcel    = dv10_parcel
                 $whereinstit
							) as diversos
                inner join arrenumcgm   on diversos.dv05_numpre = arrenumcgm.k00_numpre
                left  join arrematric   on diversos.dv05_numpre = arrematric.k00_numpre
                left  join arreinscr    on diversos.dv05_numpre = arreinscr.k00_numpre
                left  join arrepaga     on diversos.dv05_numpre = arrepaga.k00_numpre
                left  join arrecant     on diversos.dv05_numpre = arrecant.k00_numpre
                left  join arrecad      on diversos.dv05_numpre = arrecad.k00_numpre
                inner join procdiver    on dv09_procdiver       = dv05_procdiver
                inner join cgm          on dv05_numcgm          = z01_numcgm

             $dbwhere
             $ordem
           ";
    return $sql;
  }
}
?>