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

//MODULO: saude
//CLASSE DA ENTIDADE cgs_undalt
class cl_cgs_undalt { 
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
   var $z33_i_seq = 0; 
   var $z33_i_cgsund = 0; 
   var $z33_v_cgccpf = null; 
   var $z33_v_nome = null; 
   var $z33_v_ender = null; 
   var $z33_i_numero = 0; 
   var $z33_v_compl = null; 
   var $z33_v_bairro = null; 
   var $z33_v_munic = null; 
   var $z33_v_uf = null; 
   var $z33_v_cep = null; 
   var $z33_d_cadast_dia = null; 
   var $z33_d_cadast_mes = null; 
   var $z33_d_cadast_ano = null; 
   var $z33_d_cadast = null; 
   var $z33_v_telef = null; 
   var $z33_v_ident = null; 
   var $z30_i_login = 0; 
   var $z33_v_telcel = null; 
   var $z33_v_email = null; 
   var $z33_d_nasc_dia = null; 
   var $z33_d_nasc_mes = null; 
   var $z33_d_nasc_ano = null; 
   var $z33_d_nasc = null; 
   var $z33_v_sexo = null; 
   var $z33_v_tipoalt = null; 
   var $z33_i_loginalt = 0; 
   var $z33_d_dataalt_dia = null; 
   var $z33_d_dataalt_mes = null; 
   var $z33_d_dataalt_ano = null; 
   var $z33_d_dataalt = null; 
   var $z33_c_horaal = null; 
   var $z33_v_rotina = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z33_i_seq = int8 = Sequencia 
                 z33_i_cgsund = int4 = CGS 
                 z33_v_cgccpf = varchar(14) = CPF 
                 z33_v_nome = varchar(40) = Nome 
                 z33_v_ender = varchar(40) = Endereço 
                 z33_i_numero = int4 = Número 
                 z33_v_compl = varchar(20) = Complemento 
                 z33_v_bairro = varchar(40) = Bairro 
                 z33_v_munic = varchar(40) = Municipio 
                 z33_v_uf = varchar(2) = UF 
                 z33_v_cep = varchar(8) = CEP 
                 z33_d_cadast = date = Cadastro 
                 z33_v_telef = varchar(12) = Telefone 
                 z33_v_ident = varchar(20) = Identidade 
                 z30_i_login = int4 = Login 
                 z33_v_telcel = varchar(12) = Celular 
                 z33_v_email = varchar(100) = Email 
                 z33_d_nasc = date = Nascimento 
                 z33_v_sexo = varchar(1) = Sexo 
                 z33_v_tipoalt = varchar(1) = Tipo Alteração 
                 z33_i_loginalt = int4 = Login Alteração 
                 z33_d_dataalt = date = Data Alteração 
                 z33_c_horaal = char(20) = Hora Alteracao 
                 z33_v_rotina = varchar(20) = Rotina 
                 ";
   //funcao construtor da classe 
   function cl_cgs_undalt() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgs_undalt"); 
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
       $this->z33_i_seq = ($this->z33_i_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_i_seq"]:$this->z33_i_seq);
       $this->z33_i_cgsund = ($this->z33_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_i_cgsund"]:$this->z33_i_cgsund);
       $this->z33_v_cgccpf = ($this->z33_v_cgccpf == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_cgccpf"]:$this->z33_v_cgccpf);
       $this->z33_v_nome = ($this->z33_v_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_nome"]:$this->z33_v_nome);
       $this->z33_v_ender = ($this->z33_v_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_ender"]:$this->z33_v_ender);
       $this->z33_i_numero = ($this->z33_i_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_i_numero"]:$this->z33_i_numero);
       $this->z33_v_compl = ($this->z33_v_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_compl"]:$this->z33_v_compl);
       $this->z33_v_bairro = ($this->z33_v_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_bairro"]:$this->z33_v_bairro);
       $this->z33_v_munic = ($this->z33_v_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_munic"]:$this->z33_v_munic);
       $this->z33_v_uf = ($this->z33_v_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_uf"]:$this->z33_v_uf);
       $this->z33_v_cep = ($this->z33_v_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_cep"]:$this->z33_v_cep);
       if($this->z33_d_cadast == ""){
         $this->z33_d_cadast_dia = ($this->z33_d_cadast_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_cadast_dia"]:$this->z33_d_cadast_dia);
         $this->z33_d_cadast_mes = ($this->z33_d_cadast_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_cadast_mes"]:$this->z33_d_cadast_mes);
         $this->z33_d_cadast_ano = ($this->z33_d_cadast_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_cadast_ano"]:$this->z33_d_cadast_ano);
         if($this->z33_d_cadast_dia != ""){
            $this->z33_d_cadast = $this->z33_d_cadast_ano."-".$this->z33_d_cadast_mes."-".$this->z33_d_cadast_dia;
         }
       }
       $this->z33_v_telef = ($this->z33_v_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_telef"]:$this->z33_v_telef);
       $this->z33_v_ident = ($this->z33_v_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_ident"]:$this->z33_v_ident);
       $this->z30_i_login = ($this->z30_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["z30_i_login"]:$this->z30_i_login);
       $this->z33_v_telcel = ($this->z33_v_telcel == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_telcel"]:$this->z33_v_telcel);
       $this->z33_v_email = ($this->z33_v_email == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_email"]:$this->z33_v_email);
       if($this->z33_d_nasc == ""){
         $this->z33_d_nasc_dia = ($this->z33_d_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_nasc_dia"]:$this->z33_d_nasc_dia);
         $this->z33_d_nasc_mes = ($this->z33_d_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_nasc_mes"]:$this->z33_d_nasc_mes);
         $this->z33_d_nasc_ano = ($this->z33_d_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_nasc_ano"]:$this->z33_d_nasc_ano);
         if($this->z33_d_nasc_dia != ""){
            $this->z33_d_nasc = $this->z33_d_nasc_ano."-".$this->z33_d_nasc_mes."-".$this->z33_d_nasc_dia;
         }
       }
       $this->z33_v_sexo = ($this->z33_v_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_sexo"]:$this->z33_v_sexo);
       $this->z33_v_tipoalt = ($this->z33_v_tipoalt == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_tipoalt"]:$this->z33_v_tipoalt);
       $this->z33_i_loginalt = ($this->z33_i_loginalt == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_i_loginalt"]:$this->z33_i_loginalt);
       if($this->z33_d_dataalt == ""){
         $this->z33_d_dataalt_dia = ($this->z33_d_dataalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_dataalt_dia"]:$this->z33_d_dataalt_dia);
         $this->z33_d_dataalt_mes = ($this->z33_d_dataalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_dataalt_mes"]:$this->z33_d_dataalt_mes);
         $this->z33_d_dataalt_ano = ($this->z33_d_dataalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_d_dataalt_ano"]:$this->z33_d_dataalt_ano);
         if($this->z33_d_dataalt_dia != ""){
            $this->z33_d_dataalt = $this->z33_d_dataalt_ano."-".$this->z33_d_dataalt_mes."-".$this->z33_d_dataalt_dia;
         }
       }
       $this->z33_c_horaal = ($this->z33_c_horaal == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_c_horaal"]:$this->z33_c_horaal);
       $this->z33_v_rotina = ($this->z33_v_rotina == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_v_rotina"]:$this->z33_v_rotina);
     }else{
       $this->z33_i_seq = ($this->z33_i_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["z33_i_seq"]:$this->z33_i_seq);
     }
   }
   // funcao para inclusao
   function incluir ($z33_i_seq){ 
      $this->atualizacampos();
     if($this->z33_i_cgsund == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "z33_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_cgccpf == null ){ 
       $this->erro_sql = " Campo CPF nao Informado.";
       $this->erro_campo = "z33_v_cgccpf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "z33_v_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "z33_v_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_i_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "z33_i_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_compl == null ){ 
       $this->erro_sql = " Campo Complemento nao Informado.";
       $this->erro_campo = "z33_v_compl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "z33_v_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_munic == null ){ 
       $this->erro_sql = " Campo Municipio nao Informado.";
       $this->erro_campo = "z33_v_munic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_uf == null ){ 
       $this->erro_sql = " Campo UF nao Informado.";
       $this->erro_campo = "z33_v_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "z33_v_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_d_cadast == null ){ 
       $this->erro_sql = " Campo Cadastro nao Informado.";
       $this->erro_campo = "z33_d_cadast_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_telef == null ){ 
       $this->erro_sql = " Campo Telefone nao Informado.";
       $this->erro_campo = "z33_v_telef";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_ident == null ){ 
       $this->erro_sql = " Campo Identidade nao Informado.";
       $this->erro_campo = "z33_v_ident";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z30_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "z30_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_telcel == null ){ 
       $this->erro_sql = " Campo Celular nao Informado.";
       $this->erro_campo = "z33_v_telcel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_email == null ){ 
       $this->erro_sql = " Campo Email nao Informado.";
       $this->erro_campo = "z33_v_email";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_d_nasc == null ){ 
       $this->erro_sql = " Campo Nascimento nao Informado.";
       $this->erro_campo = "z33_d_nasc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_sexo == null ){ 
       $this->erro_sql = " Campo Sexo nao Informado.";
       $this->erro_campo = "z33_v_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_tipoalt == null ){ 
       $this->erro_sql = " Campo Tipo Alteração nao Informado.";
       $this->erro_campo = "z33_v_tipoalt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_i_loginalt == null ){ 
       $this->erro_sql = " Campo Login Alteração nao Informado.";
       $this->erro_campo = "z33_i_loginalt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_d_dataalt == null ){ 
       $this->erro_sql = " Campo Data Alteração nao Informado.";
       $this->erro_campo = "z33_d_dataalt_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_c_horaal == null ){ 
       $this->erro_sql = " Campo Hora Alteracao nao Informado.";
       $this->erro_campo = "z33_c_horaal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z33_v_rotina == null ){ 
       $this->erro_sql = " Campo Rotina nao Informado.";
       $this->erro_campo = "z33_v_rotina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($z33_i_seq == "" || $z33_i_seq == null ){
       $result = db_query("select nextval('cgs_undalt_z33_i_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgs_undalt_z33_i_seq do campo: z33_i_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z33_i_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgs_undalt_z33_i_seq");
       if(($result != false) && (pg_result($result,0,0) < $z33_i_seq)){
         $this->erro_sql = " Campo z33_i_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z33_i_seq = $z33_i_seq; 
       }
     }
     if(($this->z33_i_seq == null) || ($this->z33_i_seq == "") ){ 
       $this->erro_sql = " Campo z33_i_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgs_undalt(
                                       z33_i_seq 
                                      ,z33_i_cgsund 
                                      ,z33_v_cgccpf 
                                      ,z33_v_nome 
                                      ,z33_v_ender 
                                      ,z33_i_numero 
                                      ,z33_v_compl 
                                      ,z33_v_bairro 
                                      ,z33_v_munic 
                                      ,z33_v_uf 
                                      ,z33_v_cep 
                                      ,z33_d_cadast 
                                      ,z33_v_telef 
                                      ,z33_v_ident 
                                      ,z30_i_login 
                                      ,z33_v_telcel 
                                      ,z33_v_email 
                                      ,z33_d_nasc 
                                      ,z33_v_sexo 
                                      ,z33_v_tipoalt 
                                      ,z33_i_loginalt 
                                      ,z33_d_dataalt 
                                      ,z33_c_horaal 
                                      ,z33_v_rotina 
                       )
                values (
                                $this->z33_i_seq 
                               ,$this->z33_i_cgsund 
                               ,'$this->z33_v_cgccpf' 
                               ,'$this->z33_v_nome' 
                               ,'$this->z33_v_ender' 
                               ,$this->z33_i_numero 
                               ,'$this->z33_v_compl' 
                               ,'$this->z33_v_bairro' 
                               ,'$this->z33_v_munic' 
                               ,'$this->z33_v_uf' 
                               ,'$this->z33_v_cep' 
                               ,".($this->z33_d_cadast == "null" || $this->z33_d_cadast == ""?"null":"'".$this->z33_d_cadast."'")." 
                               ,'$this->z33_v_telef' 
                               ,'$this->z33_v_ident' 
                               ,$this->z30_i_login 
                               ,'$this->z33_v_telcel' 
                               ,'$this->z33_v_email' 
                               ,".($this->z33_d_nasc == "null" || $this->z33_d_nasc == ""?"null":"'".$this->z33_d_nasc."'")." 
                               ,'$this->z33_v_sexo' 
                               ,'$this->z33_v_tipoalt' 
                               ,$this->z33_i_loginalt 
                               ,".($this->z33_d_dataalt == "null" || $this->z33_d_dataalt == ""?"null":"'".$this->z33_d_dataalt."'")." 
                               ,'$this->z33_c_horaal' 
                               ,'$this->z33_v_rotina' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alterações cgs_und ($this->z33_i_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alterações cgs_und já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alterações cgs_und ($this->z33_i_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z33_i_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z33_i_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008963,'$this->z33_i_seq','I')");
       $resac = db_query("insert into db_acount values($acount,1010154,1008963,'','".AddSlashes(pg_result($resaco,0,'z33_i_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008964,'','".AddSlashes(pg_result($resaco,0,'z33_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008965,'','".AddSlashes(pg_result($resaco,0,'z33_v_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008966,'','".AddSlashes(pg_result($resaco,0,'z33_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008967,'','".AddSlashes(pg_result($resaco,0,'z33_v_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008968,'','".AddSlashes(pg_result($resaco,0,'z33_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008969,'','".AddSlashes(pg_result($resaco,0,'z33_v_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008970,'','".AddSlashes(pg_result($resaco,0,'z33_v_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008972,'','".AddSlashes(pg_result($resaco,0,'z33_v_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008973,'','".AddSlashes(pg_result($resaco,0,'z33_v_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008974,'','".AddSlashes(pg_result($resaco,0,'z33_v_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008975,'','".AddSlashes(pg_result($resaco,0,'z33_d_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008976,'','".AddSlashes(pg_result($resaco,0,'z33_v_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008977,'','".AddSlashes(pg_result($resaco,0,'z33_v_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008978,'','".AddSlashes(pg_result($resaco,0,'z30_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008979,'','".AddSlashes(pg_result($resaco,0,'z33_v_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008980,'','".AddSlashes(pg_result($resaco,0,'z33_v_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008981,'','".AddSlashes(pg_result($resaco,0,'z33_d_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008982,'','".AddSlashes(pg_result($resaco,0,'z33_v_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008983,'','".AddSlashes(pg_result($resaco,0,'z33_v_tipoalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008984,'','".AddSlashes(pg_result($resaco,0,'z33_i_loginalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008985,'','".AddSlashes(pg_result($resaco,0,'z33_d_dataalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1008986,'','".AddSlashes(pg_result($resaco,0,'z33_c_horaal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010154,1009000,'','".AddSlashes(pg_result($resaco,0,'z33_v_rotina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z33_i_seq=null) { 
      $this->atualizacampos();
     $sql = " update cgs_undalt set ";
     $virgula = "";
     if(trim($this->z33_i_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_i_seq"])){ 
       $sql  .= $virgula." z33_i_seq = $this->z33_i_seq ";
       $virgula = ",";
       if(trim($this->z33_i_seq) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "z33_i_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_i_cgsund"])){ 
       $sql  .= $virgula." z33_i_cgsund = $this->z33_i_cgsund ";
       $virgula = ",";
       if(trim($this->z33_i_cgsund) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "z33_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_cgccpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_cgccpf"])){ 
       $sql  .= $virgula." z33_v_cgccpf = '$this->z33_v_cgccpf' ";
       $virgula = ",";
       if(trim($this->z33_v_cgccpf) == null ){ 
         $this->erro_sql = " Campo CPF nao Informado.";
         $this->erro_campo = "z33_v_cgccpf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_nome"])){ 
       $sql  .= $virgula." z33_v_nome = '$this->z33_v_nome' ";
       $virgula = ",";
       if(trim($this->z33_v_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "z33_v_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_ender"])){ 
       $sql  .= $virgula." z33_v_ender = '$this->z33_v_ender' ";
       $virgula = ",";
       if(trim($this->z33_v_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "z33_v_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_i_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_i_numero"])){ 
       $sql  .= $virgula." z33_i_numero = $this->z33_i_numero ";
       $virgula = ",";
       if(trim($this->z33_i_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "z33_i_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_compl"])){ 
       $sql  .= $virgula." z33_v_compl = '$this->z33_v_compl' ";
       $virgula = ",";
       if(trim($this->z33_v_compl) == null ){ 
         $this->erro_sql = " Campo Complemento nao Informado.";
         $this->erro_campo = "z33_v_compl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_bairro"])){ 
       $sql  .= $virgula." z33_v_bairro = '$this->z33_v_bairro' ";
       $virgula = ",";
       if(trim($this->z33_v_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "z33_v_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_munic"])){ 
       $sql  .= $virgula." z33_v_munic = '$this->z33_v_munic' ";
       $virgula = ",";
       if(trim($this->z33_v_munic) == null ){ 
         $this->erro_sql = " Campo Municipio nao Informado.";
         $this->erro_campo = "z33_v_munic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_uf"])){ 
       $sql  .= $virgula." z33_v_uf = '$this->z33_v_uf' ";
       $virgula = ",";
       if(trim($this->z33_v_uf) == null ){ 
         $this->erro_sql = " Campo UF nao Informado.";
         $this->erro_campo = "z33_v_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_cep"])){ 
       $sql  .= $virgula." z33_v_cep = '$this->z33_v_cep' ";
       $virgula = ",";
       if(trim($this->z33_v_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "z33_v_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_d_cadast)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_d_cadast_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z33_d_cadast_dia"] !="") ){ 
       $sql  .= $virgula." z33_d_cadast = '$this->z33_d_cadast' ";
       $virgula = ",";
       if(trim($this->z33_d_cadast) == null ){ 
         $this->erro_sql = " Campo Cadastro nao Informado.";
         $this->erro_campo = "z33_d_cadast_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z33_d_cadast_dia"])){ 
         $sql  .= $virgula." z33_d_cadast = null ";
         $virgula = ",";
         if(trim($this->z33_d_cadast) == null ){ 
           $this->erro_sql = " Campo Cadastro nao Informado.";
           $this->erro_campo = "z33_d_cadast_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->z33_v_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_telef"])){ 
       $sql  .= $virgula." z33_v_telef = '$this->z33_v_telef' ";
       $virgula = ",";
       if(trim($this->z33_v_telef) == null ){ 
         $this->erro_sql = " Campo Telefone nao Informado.";
         $this->erro_campo = "z33_v_telef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_ident"])){ 
       $sql  .= $virgula." z33_v_ident = '$this->z33_v_ident' ";
       $virgula = ",";
       if(trim($this->z33_v_ident) == null ){ 
         $this->erro_sql = " Campo Identidade nao Informado.";
         $this->erro_campo = "z33_v_ident";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z30_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z30_i_login"])){ 
       $sql  .= $virgula." z30_i_login = $this->z30_i_login ";
       $virgula = ",";
       if(trim($this->z30_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "z30_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_telcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_telcel"])){ 
       $sql  .= $virgula." z33_v_telcel = '$this->z33_v_telcel' ";
       $virgula = ",";
       if(trim($this->z33_v_telcel) == null ){ 
         $this->erro_sql = " Campo Celular nao Informado.";
         $this->erro_campo = "z33_v_telcel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_email"])){ 
       $sql  .= $virgula." z33_v_email = '$this->z33_v_email' ";
       $virgula = ",";
       if(trim($this->z33_v_email) == null ){ 
         $this->erro_sql = " Campo Email nao Informado.";
         $this->erro_campo = "z33_v_email";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_d_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_d_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z33_d_nasc_dia"] !="") ){ 
       $sql  .= $virgula." z33_d_nasc = '$this->z33_d_nasc' ";
       $virgula = ",";
       if(trim($this->z33_d_nasc) == null ){ 
         $this->erro_sql = " Campo Nascimento nao Informado.";
         $this->erro_campo = "z33_d_nasc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z33_d_nasc_dia"])){ 
         $sql  .= $virgula." z33_d_nasc = null ";
         $virgula = ",";
         if(trim($this->z33_d_nasc) == null ){ 
           $this->erro_sql = " Campo Nascimento nao Informado.";
           $this->erro_campo = "z33_d_nasc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->z33_v_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_sexo"])){ 
       $sql  .= $virgula." z33_v_sexo = '$this->z33_v_sexo' ";
       $virgula = ",";
       if(trim($this->z33_v_sexo) == null ){ 
         $this->erro_sql = " Campo Sexo nao Informado.";
         $this->erro_campo = "z33_v_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_tipoalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_tipoalt"])){ 
       $sql  .= $virgula." z33_v_tipoalt = '$this->z33_v_tipoalt' ";
       $virgula = ",";
       if(trim($this->z33_v_tipoalt) == null ){ 
         $this->erro_sql = " Campo Tipo Alteração nao Informado.";
         $this->erro_campo = "z33_v_tipoalt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_i_loginalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_i_loginalt"])){ 
       $sql  .= $virgula." z33_i_loginalt = $this->z33_i_loginalt ";
       $virgula = ",";
       if(trim($this->z33_i_loginalt) == null ){ 
         $this->erro_sql = " Campo Login Alteração nao Informado.";
         $this->erro_campo = "z33_i_loginalt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_d_dataalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_d_dataalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["z33_d_dataalt_dia"] !="") ){ 
       $sql  .= $virgula." z33_d_dataalt = '$this->z33_d_dataalt' ";
       $virgula = ",";
       if(trim($this->z33_d_dataalt) == null ){ 
         $this->erro_sql = " Campo Data Alteração nao Informado.";
         $this->erro_campo = "z33_d_dataalt_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["z33_d_dataalt_dia"])){ 
         $sql  .= $virgula." z33_d_dataalt = null ";
         $virgula = ",";
         if(trim($this->z33_d_dataalt) == null ){ 
           $this->erro_sql = " Campo Data Alteração nao Informado.";
           $this->erro_campo = "z33_d_dataalt_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->z33_c_horaal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_c_horaal"])){ 
       $sql  .= $virgula." z33_c_horaal = '$this->z33_c_horaal' ";
       $virgula = ",";
       if(trim($this->z33_c_horaal) == null ){ 
         $this->erro_sql = " Campo Hora Alteracao nao Informado.";
         $this->erro_campo = "z33_c_horaal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z33_v_rotina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z33_v_rotina"])){ 
       $sql  .= $virgula." z33_v_rotina = '$this->z33_v_rotina' ";
       $virgula = ",";
       if(trim($this->z33_v_rotina) == null ){ 
         $this->erro_sql = " Campo Rotina nao Informado.";
         $this->erro_campo = "z33_v_rotina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z33_i_seq!=null){
       $sql .= " z33_i_seq = $this->z33_i_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z33_i_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008963,'$this->z33_i_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_i_seq"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008963,'".AddSlashes(pg_result($resaco,$conresaco,'z33_i_seq'))."','$this->z33_i_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_i_cgsund"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008964,'".AddSlashes(pg_result($resaco,$conresaco,'z33_i_cgsund'))."','$this->z33_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_cgccpf"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008965,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_cgccpf'))."','$this->z33_v_cgccpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_nome"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008966,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_nome'))."','$this->z33_v_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_ender"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008967,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_ender'))."','$this->z33_v_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_i_numero"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008968,'".AddSlashes(pg_result($resaco,$conresaco,'z33_i_numero'))."','$this->z33_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_compl"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008969,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_compl'))."','$this->z33_v_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_bairro"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008970,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_bairro'))."','$this->z33_v_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_munic"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008972,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_munic'))."','$this->z33_v_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_uf"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008973,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_uf'))."','$this->z33_v_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_cep"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008974,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_cep'))."','$this->z33_v_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_d_cadast"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008975,'".AddSlashes(pg_result($resaco,$conresaco,'z33_d_cadast'))."','$this->z33_d_cadast',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_telef"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008976,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_telef'))."','$this->z33_v_telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_ident"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008977,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_ident'))."','$this->z33_v_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z30_i_login"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008978,'".AddSlashes(pg_result($resaco,$conresaco,'z30_i_login'))."','$this->z30_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_telcel"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008979,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_telcel'))."','$this->z33_v_telcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_email"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008980,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_email'))."','$this->z33_v_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_d_nasc"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008981,'".AddSlashes(pg_result($resaco,$conresaco,'z33_d_nasc'))."','$this->z33_d_nasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_sexo"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008982,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_sexo'))."','$this->z33_v_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_tipoalt"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008983,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_tipoalt'))."','$this->z33_v_tipoalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_i_loginalt"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008984,'".AddSlashes(pg_result($resaco,$conresaco,'z33_i_loginalt'))."','$this->z33_i_loginalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_d_dataalt"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008985,'".AddSlashes(pg_result($resaco,$conresaco,'z33_d_dataalt'))."','$this->z33_d_dataalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_c_horaal"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1008986,'".AddSlashes(pg_result($resaco,$conresaco,'z33_c_horaal'))."','$this->z33_c_horaal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z33_v_rotina"]))
           $resac = db_query("insert into db_acount values($acount,1010154,1009000,'".AddSlashes(pg_result($resaco,$conresaco,'z33_v_rotina'))."','$this->z33_v_rotina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alterações cgs_und nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z33_i_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alterações cgs_und nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z33_i_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z33_i_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z33_i_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z33_i_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008963,'$z33_i_seq','E')");
         $resac = db_query("insert into db_acount values($acount,1010154,1008963,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_i_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008964,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008965,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_cgccpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008966,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008967,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008968,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008969,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008970,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008972,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008973,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008974,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008975,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_d_cadast'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008976,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008977,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008978,'','".AddSlashes(pg_result($resaco,$iresaco,'z30_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008979,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_telcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008980,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008981,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_d_nasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008982,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008983,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_tipoalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008984,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_i_loginalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008985,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_d_dataalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1008986,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_c_horaal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010154,1009000,'','".AddSlashes(pg_result($resaco,$iresaco,'z33_v_rotina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgs_undalt
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z33_i_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z33_i_seq = $z33_i_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alterações cgs_und nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z33_i_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alterações cgs_und nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z33_i_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z33_i_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgs_undalt";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>