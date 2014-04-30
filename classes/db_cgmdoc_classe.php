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

//MODULO: protocolo
//CLASSE DA ENTIDADE cgmdoc
class cl_cgmdoc { 
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
   var $z02_i_sequencial = 0; 
   var $z02_i_cgm = 0; 
   var $z02_i_pis = 0; 
   var $z02_i_cns = null; 
   var $z02_c_naturalidade = null; 
   var $z02_c_naturalidadeuf = null; 
   var $z02_i_certidaotipo = 0; 
   var $z02_c_certidaocartorio = null; 
   var $z02_c_certidaolivro = null; 
   var $z02_c_folha = null; 
   var $z02_c_termo = null; 
   var $z02_d_certidaodata_dia = null; 
   var $z02_d_certidaodata_mes = null; 
   var $z02_d_certidaodata_ano = null; 
   var $z02_d_certidaodata = null; 
   var $z02_c_identuf = null; 
   var $z02_c_identorgao = null; 
   var $z02_d_identdata_dia = null; 
   var $z02_d_identdata_mes = null; 
   var $z02_d_identdata_ano = null; 
   var $z02_d_identdata = null; 
   var $z02_c_pais = null; 
   var $z02_d_dataentrada_dia = null; 
   var $z02_d_dataentrada_mes = null; 
   var $z02_d_dataentrada_ano = null; 
   var $z02_d_dataentrada = null; 
   var $z02_c_banco = null; 
   var $z02_c_agencia = null; 
   var $z02_c_contacorrente = null; 
   var $z02_c_ctpsnum = null; 
   var $z02_c_ctpsserie = null; 
   var $z02_c_ctpsuf = null; 
   var $z02_d_ctpsdata_dia = null; 
   var $z02_d_ctpsdata_mes = null; 
   var $z02_d_ctpsdata_ano = null; 
   var $z02_d_ctpsdata = null; 
   var $z02_i_escolaridade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z02_i_sequencial = int4 = Sequencial 
                 z02_i_cgm = int4 = CGM 
                 z02_i_pis = int4 = PIS / PASEP 
                 z02_i_cns = char(15) = CNS 
                 z02_c_naturalidade = char(50) = Naturalidade 
                 z02_c_naturalidadeuf = char(2) = UF Naturalidade 
                 z02_i_certidaotipo = int4 = Tipo de Certidão 
                 z02_c_certidaocartorio = char(50) = Nome do Cartório 
                 z02_c_certidaolivro = char(20) = Livro 
                 z02_c_folha = char(20) = Folha 
                 z02_c_termo = char(20) = Termo 
                 z02_d_certidaodata = date = Data de Emissão 
                 z02_c_identuf = char(2) = UF da Identidade 
                 z02_c_identorgao = char(50) = Órgão Emissor 
                 z02_d_identdata = date = Data de Expedição 
                 z02_c_pais = char(50) = País de Origem 
                 z02_d_dataentrada = date = Entrada no País 
                 z02_c_banco = char(40) = Banco 
                 z02_c_agencia = char(10) = Agência 
                 z02_c_contacorrente = char(30) = N° Conta Corrente 
                 z02_c_ctpsnum = char(20) = CTPS N° 
                 z02_c_ctpsserie = char(10) = CTPS Série 
                 z02_c_ctpsuf = char(2) = CTPS UF 
                 z02_d_ctpsdata = date = CTPS Data de Emissão 
                 z02_i_escolaridade = int4 = Escolaridade 
                 ";
   //funcao construtor da classe 
   function cl_cgmdoc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgmdoc"); 
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
       $this->z02_i_sequencial = ($this->z02_i_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_i_sequencial"]:$this->z02_i_sequencial);
       $this->z02_i_cgm = ($this->z02_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_i_cgm"]:$this->z02_i_cgm);
       $this->z02_i_pis = ($this->z02_i_pis == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_i_pis"]:$this->z02_i_pis);
       $this->z02_i_cns = ($this->z02_i_cns == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_i_cns"]:$this->z02_i_cns);
       $this->z02_c_naturalidade = ($this->z02_c_naturalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_naturalidade"]:$this->z02_c_naturalidade);
       $this->z02_c_naturalidadeuf = ($this->z02_c_naturalidadeuf == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_naturalidadeuf"]:$this->z02_c_naturalidadeuf);
       $this->z02_i_certidaotipo = ($this->z02_i_certidaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_i_certidaotipo"]:$this->z02_i_certidaotipo);
       $this->z02_c_certidaocartorio = ($this->z02_c_certidaocartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_certidaocartorio"]:$this->z02_c_certidaocartorio);
       $this->z02_c_certidaolivro = ($this->z02_c_certidaolivro == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_certidaolivro"]:$this->z02_c_certidaolivro);
       $this->z02_c_folha = ($this->z02_c_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_folha"]:$this->z02_c_folha);
       $this->z02_c_termo = ($this->z02_c_termo == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_termo"]:$this->z02_c_termo);
       if($this->z02_d_certidaodata == ""){
         $this->z02_d_certidaodata_dia = ($this->z02_d_certidaodata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_certidaodata_dia"]:$this->z02_d_certidaodata_dia);
         $this->z02_d_certidaodata_mes = ($this->z02_d_certidaodata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_certidaodata_mes"]:$this->z02_d_certidaodata_mes);
         $this->z02_d_certidaodata_ano = ($this->z02_d_certidaodata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_certidaodata_ano"]:$this->z02_d_certidaodata_ano);
         if($this->z02_d_certidaodata_dia != ""){
            $this->z02_d_certidaodata = $this->z02_d_certidaodata_ano."-".$this->z02_d_certidaodata_mes."-".$this->z02_d_certidaodata_dia;
         }
       }
       $this->z02_c_identuf = ($this->z02_c_identuf == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_identuf"]:$this->z02_c_identuf);
       $this->z02_c_identorgao = ($this->z02_c_identorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_identorgao"]:$this->z02_c_identorgao);
       if($this->z02_d_identdata == ""){
         $this->z02_d_identdata_dia = ($this->z02_d_identdata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_identdata_dia"]:$this->z02_d_identdata_dia);
         $this->z02_d_identdata_mes = ($this->z02_d_identdata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_identdata_mes"]:$this->z02_d_identdata_mes);
         $this->z02_d_identdata_ano = ($this->z02_d_identdata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_identdata_ano"]:$this->z02_d_identdata_ano);
         if($this->z02_d_identdata_dia != ""){
            $this->z02_d_identdata = $this->z02_d_identdata_ano."-".$this->z02_d_identdata_mes."-".$this->z02_d_identdata_dia;
         }
       }
       $this->z02_c_pais = ($this->z02_c_pais == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_pais"]:$this->z02_c_pais);
       if($this->z02_d_dataentrada == ""){
         $this->z02_d_dataentrada_dia = ($this->z02_d_dataentrada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_dataentrada_dia"]:$this->z02_d_dataentrada_dia);
         $this->z02_d_dataentrada_mes = ($this->z02_d_dataentrada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_dataentrada_mes"]:$this->z02_d_dataentrada_mes);
         $this->z02_d_dataentrada_ano = ($this->z02_d_dataentrada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_dataentrada_ano"]:$this->z02_d_dataentrada_ano);
         if($this->z02_d_dataentrada_dia != ""){
            $this->z02_d_dataentrada = $this->z02_d_dataentrada_ano."-".$this->z02_d_dataentrada_mes."-".$this->z02_d_dataentrada_dia;
         }
       }
       $this->z02_c_banco = ($this->z02_c_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_banco"]:$this->z02_c_banco);
       $this->z02_c_agencia = ($this->z02_c_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_agencia"]:$this->z02_c_agencia);
       $this->z02_c_contacorrente = ($this->z02_c_contacorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_contacorrente"]:$this->z02_c_contacorrente);
       $this->z02_c_ctpsnum = ($this->z02_c_ctpsnum == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsnum"]:$this->z02_c_ctpsnum);
       $this->z02_c_ctpsserie = ($this->z02_c_ctpsserie == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsserie"]:$this->z02_c_ctpsserie);
       $this->z02_c_ctpsuf = ($this->z02_c_ctpsuf == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsuf"]:$this->z02_c_ctpsuf);
       if($this->z02_d_ctpsdata == ""){
         $this->z02_d_ctpsdata_dia = ($this->z02_d_ctpsdata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_ctpsdata_dia"]:$this->z02_d_ctpsdata_dia);
         $this->z02_d_ctpsdata_mes = ($this->z02_d_ctpsdata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_ctpsdata_mes"]:$this->z02_d_ctpsdata_mes);
         $this->z02_d_ctpsdata_ano = ($this->z02_d_ctpsdata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_d_ctpsdata_ano"]:$this->z02_d_ctpsdata_ano);
         if($this->z02_d_ctpsdata_dia != ""){
            $this->z02_d_ctpsdata = $this->z02_d_ctpsdata_ano."-".$this->z02_d_ctpsdata_mes."-".$this->z02_d_ctpsdata_dia;
         }
       }
       $this->z02_i_escolaridade = ($this->z02_i_escolaridade == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_i_escolaridade"]:$this->z02_i_escolaridade);
     }else{
       $this->z02_i_sequencial = ($this->z02_i_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z02_i_sequencial"]:$this->z02_i_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($z02_i_sequencial){ 
      $this->atualizacampos();
     if($this->z02_i_cgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "z02_i_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z02_i_pis == null ){ 
       $this->z02_i_pis = "0";
     }
     if($this->z02_i_cns == null ){ 
       $this->z02_i_cns = "null";
     }
     if($this->z02_i_certidaotipo == null ){ 
       $this->z02_i_certidaotipo = "0";
     }
     if($this->z02_d_certidaodata == null ){ 
       $this->z02_d_certidaodata = "null";
     }
     if($this->z02_d_identdata == null ){ 
       $this->z02_d_identdata = "null";
     }
     if($this->z02_d_dataentrada == null ){ 
       $this->z02_d_dataentrada = "null";
     }
     if($this->z02_d_ctpsdata == null ){ 
       $this->z02_d_ctpsdata = "null";
     }
     if($this->z02_i_escolaridade == null ){ 
       $this->z02_i_escolaridade = "0";
     }
     if($z02_i_sequencial == "" || $z02_i_sequencial == null ){
       $result = db_query("select nextval('cgmdoc_z02_i_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmdoc_z02_i_sequencial_seq do campo: z02_i_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z02_i_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgmdoc_z02_i_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $z02_i_sequencial)){
         $this->erro_sql = " Campo z02_i_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z02_i_sequencial = $z02_i_sequencial; 
       }
     }
     if(($this->z02_i_sequencial == null) || ($this->z02_i_sequencial == "") ){ 
       $this->erro_sql = " Campo z02_i_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmdoc(
                                       z02_i_sequencial 
                                      ,z02_i_cgm 
                                      ,z02_i_pis 
                                      ,z02_i_cns 
                                      ,z02_c_naturalidade 
                                      ,z02_c_naturalidadeuf 
                                      ,z02_i_certidaotipo 
                                      ,z02_c_certidaocartorio 
                                      ,z02_c_certidaolivro 
                                      ,z02_c_folha 
                                      ,z02_c_termo 
                                      ,z02_d_certidaodata 
                                      ,z02_c_identuf 
                                      ,z02_c_identorgao 
                                      ,z02_d_identdata 
                                      ,z02_c_pais 
                                      ,z02_d_dataentrada 
                                      ,z02_c_banco 
                                      ,z02_c_agencia 
                                      ,z02_c_contacorrente 
                                      ,z02_c_ctpsnum 
                                      ,z02_c_ctpsserie 
                                      ,z02_c_ctpsuf 
                                      ,z02_d_ctpsdata 
                                      ,z02_i_escolaridade 
                       )
                values (
                                $this->z02_i_sequencial 
                               ,$this->z02_i_cgm 
                               ,$this->z02_i_pis 
                               ,$this->z02_i_cns 
                               ,'$this->z02_c_naturalidade' 
                               ,'$this->z02_c_naturalidadeuf' 
                               ,$this->z02_i_certidaotipo 
                               ,'$this->z02_c_certidaocartorio' 
                               ,'$this->z02_c_certidaolivro' 
                               ,'$this->z02_c_folha' 
                               ,'$this->z02_c_termo' 
                               ,".($this->z02_d_certidaodata == "null" || $this->z02_d_certidaodata == ""?"null":"'".$this->z02_d_certidaodata."'")." 
                               ,'$this->z02_c_identuf' 
                               ,'$this->z02_c_identorgao' 
                               ,".($this->z02_d_identdata == "null" || $this->z02_d_identdata == ""?"null":"'".$this->z02_d_identdata."'")." 
                               ,'$this->z02_c_pais' 
                               ,".($this->z02_d_dataentrada == "null" || $this->z02_d_dataentrada == ""?"null":"'".$this->z02_d_dataentrada."'")." 
                               ,'$this->z02_c_banco' 
                               ,'$this->z02_c_agencia' 
                               ,'$this->z02_c_contacorrente' 
                               ,'$this->z02_c_ctpsnum' 
                               ,'$this->z02_c_ctpsserie' 
                               ,'$this->z02_c_ctpsuf' 
                               ,".($this->z02_d_ctpsdata == "null" || $this->z02_d_ctpsdata == ""?"null":"'".$this->z02_d_ctpsdata."'")." 
                               ,$this->z02_i_escolaridade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados Adicionais do CGM  ($this->z02_i_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados Adicionais do CGM  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados Adicionais do CGM  ($this->z02_i_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z02_i_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z02_i_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11349,'$this->z02_i_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1951,11349,'','".AddSlashes(pg_result($resaco,0,'z02_i_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11350,'','".AddSlashes(pg_result($resaco,0,'z02_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11351,'','".AddSlashes(pg_result($resaco,0,'z02_i_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11352,'','".AddSlashes(pg_result($resaco,0,'z02_i_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11353,'','".AddSlashes(pg_result($resaco,0,'z02_c_naturalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11354,'','".AddSlashes(pg_result($resaco,0,'z02_c_naturalidadeuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11355,'','".AddSlashes(pg_result($resaco,0,'z02_i_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11356,'','".AddSlashes(pg_result($resaco,0,'z02_c_certidaocartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11357,'','".AddSlashes(pg_result($resaco,0,'z02_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11358,'','".AddSlashes(pg_result($resaco,0,'z02_c_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11359,'','".AddSlashes(pg_result($resaco,0,'z02_c_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11360,'','".AddSlashes(pg_result($resaco,0,'z02_d_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11361,'','".AddSlashes(pg_result($resaco,0,'z02_c_identuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11362,'','".AddSlashes(pg_result($resaco,0,'z02_c_identorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11363,'','".AddSlashes(pg_result($resaco,0,'z02_d_identdata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11364,'','".AddSlashes(pg_result($resaco,0,'z02_c_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11365,'','".AddSlashes(pg_result($resaco,0,'z02_d_dataentrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11366,'','".AddSlashes(pg_result($resaco,0,'z02_c_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11367,'','".AddSlashes(pg_result($resaco,0,'z02_c_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11368,'','".AddSlashes(pg_result($resaco,0,'z02_c_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11369,'','".AddSlashes(pg_result($resaco,0,'z02_c_ctpsnum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11370,'','".AddSlashes(pg_result($resaco,0,'z02_c_ctpsserie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11371,'','".AddSlashes(pg_result($resaco,0,'z02_c_ctpsuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11372,'','".AddSlashes(pg_result($resaco,0,'z02_d_ctpsdata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1951,11373,'','".AddSlashes(pg_result($resaco,0,'z02_i_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z02_i_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cgmdoc set ";
     $virgula = "";
     if(trim($this->z02_i_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_i_sequencial"])){ 
       $sql  .= $virgula." z02_i_sequencial = $this->z02_i_sequencial ";
       $virgula = ",";
       if(trim($this->z02_i_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "z02_i_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z02_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_i_cgm"])){ 
       $sql  .= $virgula." z02_i_cgm = $this->z02_i_cgm ";
       $virgula = ",";
       if(trim($this->z02_i_cgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "z02_i_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z02_i_pis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_i_pis"])){ 
        if(trim($this->z02_i_pis)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z02_i_pis"])){ 
           $this->z02_i_pis = "0" ; 
        } 
       $sql  .= $virgula." z02_i_pis = $this->z02_i_pis ";
       $virgula = ",";
     }
     if(trim($this->z02_i_cns)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_i_cns"])){ 
       $sql  .= $virgula." z02_i_cns = $this->z02_i_cns ";
       $virgula = ",";
     }
     if(trim($this->z02_c_naturalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_naturalidade"])){ 
       $sql  .= $virgula." z02_c_naturalidade = '$this->z02_c_naturalidade' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_naturalidadeuf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_naturalidadeuf"])){ 
       $sql  .= $virgula." z02_c_naturalidadeuf = '$this->z02_c_naturalidadeuf' ";
       $virgula = ",";
     }
     if(trim($this->z02_i_certidaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_i_certidaotipo"])){ 
        if(trim($this->z02_i_certidaotipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z02_i_certidaotipo"])){ 
           $this->z02_i_certidaotipo = "0" ; 
        } 
       $sql  .= $virgula." z02_i_certidaotipo = $this->z02_i_certidaotipo ";
       $virgula = ",";
     }
     if(trim($this->z02_c_certidaocartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_certidaocartorio"])){ 
       $sql  .= $virgula." z02_c_certidaocartorio = '$this->z02_c_certidaocartorio' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_certidaolivro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_certidaolivro"])){ 
       $sql  .= $virgula." z02_c_certidaolivro = '$this->z02_c_certidaolivro' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_folha"])){ 
       $sql  .= $virgula." z02_c_folha = '$this->z02_c_folha' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_termo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_termo"])){ 
       $sql  .= $virgula." z02_c_termo = '$this->z02_c_termo' ";
       $virgula = ",";
     }
     if(trim($this->z02_d_certidaodata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_d_certidaodata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z02_d_certidaodata_dia"] !="") ){ 
       $sql  .= $virgula." z02_d_certidaodata = '$this->z02_d_certidaodata' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_certidaodata_dia"])){ 
         $sql  .= $virgula." z02_d_certidaodata = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z02_c_identuf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_identuf"])){ 
       $sql  .= $virgula." z02_c_identuf = '$this->z02_c_identuf' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_identorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_identorgao"])){ 
       $sql  .= $virgula." z02_c_identorgao = '$this->z02_c_identorgao' ";
       $virgula = ",";
     }
     if(trim($this->z02_d_identdata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_d_identdata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z02_d_identdata_dia"] !="") ){ 
       $sql  .= $virgula." z02_d_identdata = '$this->z02_d_identdata' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_identdata_dia"])){ 
         $sql  .= $virgula." z02_d_identdata = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z02_c_pais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_pais"])){ 
       $sql  .= $virgula." z02_c_pais = '$this->z02_c_pais' ";
       $virgula = ",";
     }
     if(trim($this->z02_d_dataentrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_d_dataentrada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z02_d_dataentrada_dia"] !="") ){ 
       $sql  .= $virgula." z02_d_dataentrada = '$this->z02_d_dataentrada' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_dataentrada_dia"])){ 
         $sql  .= $virgula." z02_d_dataentrada = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z02_c_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_banco"])){ 
       $sql  .= $virgula." z02_c_banco = '$this->z02_c_banco' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_agencia"])){ 
       $sql  .= $virgula." z02_c_agencia = '$this->z02_c_agencia' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_contacorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_contacorrente"])){ 
       $sql  .= $virgula." z02_c_contacorrente = '$this->z02_c_contacorrente' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_ctpsnum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsnum"])){ 
       $sql  .= $virgula." z02_c_ctpsnum = '$this->z02_c_ctpsnum' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_ctpsserie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsserie"])){ 
       $sql  .= $virgula." z02_c_ctpsserie = '$this->z02_c_ctpsserie' ";
       $virgula = ",";
     }
     if(trim($this->z02_c_ctpsuf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsuf"])){ 
       $sql  .= $virgula." z02_c_ctpsuf = '$this->z02_c_ctpsuf' ";
       $virgula = ",";
     }
     if(trim($this->z02_d_ctpsdata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_d_ctpsdata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z02_d_ctpsdata_dia"] !="") ){ 
       $sql  .= $virgula." z02_d_ctpsdata = '$this->z02_d_ctpsdata' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_ctpsdata_dia"])){ 
         $sql  .= $virgula." z02_d_ctpsdata = null ";
         $virgula = ",";
       }
     }
     if(trim($this->z02_i_escolaridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z02_i_escolaridade"])){ 
        if(trim($this->z02_i_escolaridade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z02_i_escolaridade"])){ 
           $this->z02_i_escolaridade = "0" ; 
        } 
       $sql  .= $virgula." z02_i_escolaridade = $this->z02_i_escolaridade ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($z02_i_sequencial!=null){
       $sql .= " z02_i_sequencial = $this->z02_i_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z02_i_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11349,'$this->z02_i_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_i_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1951,11349,'".AddSlashes(pg_result($resaco,$conresaco,'z02_i_sequencial'))."','$this->z02_i_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_i_cgm"]))
           $resac = db_query("insert into db_acount values($acount,1951,11350,'".AddSlashes(pg_result($resaco,$conresaco,'z02_i_cgm'))."','$this->z02_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_i_pis"]))
           $resac = db_query("insert into db_acount values($acount,1951,11351,'".AddSlashes(pg_result($resaco,$conresaco,'z02_i_pis'))."','$this->z02_i_pis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_i_cns"]))
           $resac = db_query("insert into db_acount values($acount,1951,11352,'".AddSlashes(pg_result($resaco,$conresaco,'z02_i_cns'))."','$this->z02_i_cns',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_naturalidade"]))
           $resac = db_query("insert into db_acount values($acount,1951,11353,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_naturalidade'))."','$this->z02_c_naturalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_naturalidadeuf"]))
           $resac = db_query("insert into db_acount values($acount,1951,11354,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_naturalidadeuf'))."','$this->z02_c_naturalidadeuf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_i_certidaotipo"]))
           $resac = db_query("insert into db_acount values($acount,1951,11355,'".AddSlashes(pg_result($resaco,$conresaco,'z02_i_certidaotipo'))."','$this->z02_i_certidaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_certidaocartorio"]))
           $resac = db_query("insert into db_acount values($acount,1951,11356,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_certidaocartorio'))."','$this->z02_c_certidaocartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_certidaolivro"]))
           $resac = db_query("insert into db_acount values($acount,1951,11357,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_certidaolivro'))."','$this->z02_c_certidaolivro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_folha"]))
           $resac = db_query("insert into db_acount values($acount,1951,11358,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_folha'))."','$this->z02_c_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_termo"]))
           $resac = db_query("insert into db_acount values($acount,1951,11359,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_termo'))."','$this->z02_c_termo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_certidaodata"]))
           $resac = db_query("insert into db_acount values($acount,1951,11360,'".AddSlashes(pg_result($resaco,$conresaco,'z02_d_certidaodata'))."','$this->z02_d_certidaodata',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_identuf"]))
           $resac = db_query("insert into db_acount values($acount,1951,11361,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_identuf'))."','$this->z02_c_identuf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_identorgao"]))
           $resac = db_query("insert into db_acount values($acount,1951,11362,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_identorgao'))."','$this->z02_c_identorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_identdata"]))
           $resac = db_query("insert into db_acount values($acount,1951,11363,'".AddSlashes(pg_result($resaco,$conresaco,'z02_d_identdata'))."','$this->z02_d_identdata',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_pais"]))
           $resac = db_query("insert into db_acount values($acount,1951,11364,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_pais'))."','$this->z02_c_pais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_dataentrada"]))
           $resac = db_query("insert into db_acount values($acount,1951,11365,'".AddSlashes(pg_result($resaco,$conresaco,'z02_d_dataentrada'))."','$this->z02_d_dataentrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_banco"]))
           $resac = db_query("insert into db_acount values($acount,1951,11366,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_banco'))."','$this->z02_c_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_agencia"]))
           $resac = db_query("insert into db_acount values($acount,1951,11367,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_agencia'))."','$this->z02_c_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_contacorrente"]))
           $resac = db_query("insert into db_acount values($acount,1951,11368,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_contacorrente'))."','$this->z02_c_contacorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsnum"]))
           $resac = db_query("insert into db_acount values($acount,1951,11369,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_ctpsnum'))."','$this->z02_c_ctpsnum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsserie"]))
           $resac = db_query("insert into db_acount values($acount,1951,11370,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_ctpsserie'))."','$this->z02_c_ctpsserie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_c_ctpsuf"]))
           $resac = db_query("insert into db_acount values($acount,1951,11371,'".AddSlashes(pg_result($resaco,$conresaco,'z02_c_ctpsuf'))."','$this->z02_c_ctpsuf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_d_ctpsdata"]))
           $resac = db_query("insert into db_acount values($acount,1951,11372,'".AddSlashes(pg_result($resaco,$conresaco,'z02_d_ctpsdata'))."','$this->z02_d_ctpsdata',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z02_i_escolaridade"]))
           $resac = db_query("insert into db_acount values($acount,1951,11373,'".AddSlashes(pg_result($resaco,$conresaco,'z02_i_escolaridade'))."','$this->z02_i_escolaridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Adicionais do CGM  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z02_i_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Adicionais do CGM  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z02_i_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z02_i_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z02_i_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z02_i_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11349,'$z02_i_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1951,11349,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_i_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11350,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11351,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_i_pis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11352,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_i_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11353,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_naturalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11354,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_naturalidadeuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11355,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_i_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11356,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_certidaocartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11357,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11358,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11359,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11360,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_d_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11361,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_identuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11362,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_identorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11363,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_d_identdata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11364,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11365,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_d_dataentrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11366,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11367,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11368,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11369,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_ctpsnum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11370,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_ctpsserie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11371,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_c_ctpsuf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11372,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_d_ctpsdata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1951,11373,'','".AddSlashes(pg_result($resaco,$iresaco,'z02_i_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgmdoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z02_i_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z02_i_sequencial = $z02_i_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Adicionais do CGM  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z02_i_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Adicionais do CGM  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z02_i_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z02_i_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmdoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $z02_i_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmdoc ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cgmdoc.z02_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($z02_i_sequencial!=null ){
         $sql2 .= " where cgmdoc.z02_i_sequencial = $z02_i_sequencial "; 
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
   function sql_query_file ( $z02_i_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmdoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($z02_i_sequencial!=null ){
         $sql2 .= " where cgmdoc.z02_i_sequencial = $z02_i_sequencial "; 
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